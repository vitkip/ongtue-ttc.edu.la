<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Support\MaterialSymbolIcons;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'ເນື້ອຫາເວັບໄຊ';

    protected static ?string $navigationLabel = 'ຫຼັກສູດ (Courses)';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ຂໍ້ມູນຫຼັກສູດ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('ໝວດໝູ່')
                            ->relationship('category', 'name', fn (Builder $query) => $query->where('is_active', true)->orderBy('sort_order'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('badge_label')
                            ->label('ປ້າຍກຳກັບ')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('title')
                            ->label('ຊື່ຫຼັກສູດ')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('ລາຍລະອຽດ')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('ຄ່າຮຽນ ແລະ ໄລຍະເວລາ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('fee_type')
                            ->label('ປະເພດຄ່າຮຽນ')
                            ->options([
                                'free' => 'ຮຽນຟຣີ (Free)',
                                'scholarship' => 'ທຶນການສຶກສາ (Scholarship)',
                                'paid' => 'ເສຍຄ່າຮຽນ (Paid)',
                            ])
                            ->required()
                            ->default('paid')
                            ->live(),
                        Forms\Components\Select::make('icon')
                            ->label('ໄອຄອນ')
                            ->options(function (?Course $record, Get $get) {
                                $options = MaterialSymbolIcons::options();
                                $current = $get('icon') ?? $record?->icon;

                                if (filled($current) && ! MaterialSymbolIcons::exists($current)) {
                                    $options['ປັດຈຸບັນ'] = [
                                        $current => MaterialSymbolIcons::renderOption($current),
                                    ];
                                }

                                return $options;
                            })
                            ->searchable()
                            ->allowHtml()
                            ->native(false)
                            ->live()
                            ->placeholder('ເລືອກໄອຄອນ...')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('ຊື່ໄອຄອນ (Material Symbols)')
                                    ->helperText('ໃສ່ຊື່ໄອຄອນຈາກ Google Material Symbols, ຕົວຢ່າງ: payments')
                                    ->required(),
                            ])
                            ->createOptionUsing(fn (array $data) => $data['name'])
                            ->hint(fn (Get $get) => filled($get('icon'))
                                ? new HtmlString('<span class="material-symbols-outlined align-middle text-2xl">'.e($get('icon')).'</span>')
                                : null)
                            ->default('payments'),
                        Forms\Components\TextInput::make('fee_label')
                            ->label('ລາຍລະອຽດຄ່າຮຽນ')
                            ->maxLength(150)
                            ->default(null),
                        Forms\Components\TextInput::make('duration_label')
                            ->label('ໄລຍະເວລາຮຽນ')
                            ->maxLength(150)
                            ->default(null),
                    ]),
                Forms\Components\Section::make('ຮູບພາບ ແລະ ການສະແດງຜົນ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image_url')
                            ->label('ຮູບພາບ')
                            ->image()
                            ->disk('public')
                            ->directory('courses')
                            ->imagePreviewHeight('250')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('ຫຼັກສູດແນະນຳ')
                            ->helperText('ສະແດງເປັນຈຸດເດັ່ນຢູ່ໜ້າຫຼັກ')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('ເປີດການສະແດງຜົນ')
                            ->helperText('ປິດໄວ້ຈະບໍ່ສະແດງຢູ່ໜ້າເວັບ')
                            ->required()
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('ລຳດັບການສະແດງ')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('ຊື່ຫຼັກສູດ')
                    ->searchable()
                    ->description(fn (Course $record): string => $record->badge_label ?? ''),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('ໝວດໝູ່')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fee_type')
                    ->label('ຄ່າຮຽນ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'free' => 'success',
                        'scholarship' => 'info',
                        'paid' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('duration_label')
                    ->label('ໄລຍະເວລາ')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('ແນະນຳ')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('ເປີດການສະແດງຜົນ')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ລຳດັບ')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('ໝວດໝູ່')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('fee_type')
                    ->options([
                        'free' => 'ຮຽນຟຣີ',
                        'scholarship' => 'ທຶນການສຶກສາ',
                        'paid' => 'ເສຍຄ່າຮຽນ',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('ສະຖານະການສະແດງຜົນ'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('ຫຼັກສູດແນະນຳ'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Course $record) {
                        if ($record->applications()->exists()) {
                            Notification::make()
                                ->title('ບໍ່ສາມາດລຶບໄດ້')
                                ->body('ຫຼັກສູດນີ້ຍັງມີໃບສະໝັກຜູກຢູ່')
                                ->danger()
                                ->send();

                            throw new Halt();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            if ($records->contains(fn (Course $record) => $record->applications()->exists())) {
                                Notification::make()
                                    ->title('ບໍ່ສາມາດລຶບໄດ້')
                                    ->body('ມີບາງຫຼັກສູດທີ່ຍັງມີໃບສະໝັກຜູກຢູ່')
                                    ->danger()
                                    ->send();

                                throw new Halt();
                            }
                        }),
                ]),
            ])
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
