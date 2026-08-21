<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCategoryResource\Pages;
use App\Filament\Resources\CourseCategoryResource\RelationManagers;
use App\Models\CourseCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CourseCategoryResource extends Resource
{
    protected static ?string $model = CourseCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'ເນື້ອຫາເວັບໄຊ';

    protected static ?string $navigationLabel = 'ໝວດໝູ່ຫຼັກສູດ (Categories)';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('ຊື່ໝວດໝູ່')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->helperText('ໃຊ້ໃນລະຫັດອ້າງອີງ (ຕົວອັກສອນພາສາອັງກິດ, ຂີດກາງ)'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('ລຳດັບການສະແດງ')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('ເປີດການໃຊ້ງານ')
                            ->helperText('ປິດໄວ້ຈະບໍ່ສະແດງໃນລາຍການໃຫ້ເລືອກ')
                            ->required()
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ຊື່ໝວດໝູ່')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('courses_count')
                    ->label('ຈຳນວນຫຼັກສູດ')
                    ->counts('courses')
                    ->numeric(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ລຳດັບ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('ເປີດການໃຊ້ງານ')
                    ->boolean(),
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('ສະຖານະການໃຊ້ງານ'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (CourseCategory $record) {
                        if ($record->courses()->exists()) {
                            Notification::make()
                                ->title('ບໍ່ສາມາດລຶບໄດ້')
                                ->body('ໝວດໝູ່ນີ້ຍັງມີຫຼັກສູດຜູກຢູ່ ກະລຸນາຍ້າຍ ຫຼື ລຶບຫຼັກສູດອອກກ່ອນ')
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
                            if ($records->contains(fn (CourseCategory $record) => $record->courses()->exists())) {
                                Notification::make()
                                    ->title('ບໍ່ສາມາດລຶບໄດ້')
                                    ->body('ມີບາງໝວດໝູ່ທີ່ຍັງມີຫຼັກສູດຜູກຢູ່ ກະລຸນາຍ້າຍ ຫຼື ລຶບຫຼັກສູດອອກກ່ອນ')
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
            'index' => Pages\ListCourseCategories::route('/'),
            'create' => Pages\CreateCourseCategory::route('/create'),
            'edit' => Pages\EditCourseCategory::route('/{record}/edit'),
        ];
    }
}
