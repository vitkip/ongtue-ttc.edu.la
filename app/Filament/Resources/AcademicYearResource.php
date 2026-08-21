<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicYearResource\Pages;
use App\Models\AcademicYear;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'ເນື້ອຫາເວັບໄຊ';

    protected static ?string $navigationLabel = 'ປີການສຶກສາ (Academic Years)';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('ປີການສຶກສາ')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('2026-2027'),
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
                Tables\Columns\TextColumn::make('label')
                    ->label('ປີການສຶກສາ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('ຈຳນວນໃບສະໝັກ')
                    ->counts('applications')
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
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('ສະຖານະການໃຊ້ງານ'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (AcademicYear $record) {
                        if ($record->applications()->exists()) {
                            Notification::make()
                                ->title('ບໍ່ສາມາດລຶບໄດ້')
                                ->body('ປີການສຶກສານີ້ຍັງມີໃບສະໝັກຜູກຢູ່')
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
                            if ($records->contains(fn (AcademicYear $record) => $record->applications()->exists())) {
                                Notification::make()
                                    ->title('ບໍ່ສາມາດລຶບໄດ້')
                                    ->body('ມີບາງປີການສຶກສາທີ່ຍັງມີໃບສະໝັກຜູກຢູ່')
                                    ->danger()
                                    ->send();

                                throw new Halt();
                            }
                        }),
                ]),
            ])
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicYears::route('/'),
            'create' => Pages\CreateAcademicYear::route('/create'),
            'edit' => Pages\EditAcademicYear::route('/{record}/edit'),
        ];
    }
}
