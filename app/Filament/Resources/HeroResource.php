<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroResource\Pages;
use App\Models\Hero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroResource extends Resource
{
    protected static ?string $model = Hero::class;

    protected static ?string $navigationIcon = 'heroicon-o-window';

    protected static ?string $navigationGroup = 'ເນື້ອຫາເວັບໄຊ';

    protected static ?string $navigationLabel = 'Hero (ໜ້າຫຼັກ)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image_url')
                            ->label('ຮູບພື້ນຫຼັງ')
                            ->helperText('ຖ້າຮູບເດີມເປັນລິ້ງພາຍນອກ (ບໍ່ແມ່ນໄຟລ໌ທີ່ອັບໂຫຼດຜ່ານລະບົບ), ຈະບໍ່ສະແດງຕົວຢ່າງຢູ່ນີ້ — ປ່ອຍວ່າງໄວ້ເພື່ອຮັກສາຮູບເດີມ, ຫຼືອັບໂຫຼດຮູບໃໝ່ເພື່ອປ່ຽນແທນ.')
                            ->image()
                            ->disk('public')
                            ->directory('hero-images')
                            ->imagePreviewHeight('250')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('badge_text')
                            ->label('ຂໍ້ຄວາມປ້າຍ (Badge)')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('title_line1')
                            ->label('ຫົວຂໍ້ ແຖວທີ 1')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('title_line2')
                            ->label('ຫົວຂໍ້ ແຖວທີ 2 (ໄຮໄລ້)')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('ຄຳອະທິບາຍ')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('primary_button_text')
                            ->label('ຂໍ້ຄວາມປຸ່ມທີ 1 (ສະໝັກຮຽນ)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('primary_button_link')
                            ->label('ລິ້ງປຸ່ມທີ 1')
                            ->helperText('ປ່ອຍວ່າງໄວ້ = ກົດແລ້ວເປີດຟອມສະໝັກຮຽນ (ຄ່າເລີ່ມຕົ້ນ). ຖ້າໃສ່ລິ້ງ = ກົດແລ້ວໄປໜ້ານັ້ນແທນ')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('secondary_button_text')
                            ->label('ຂໍ້ຄວາມປຸ່ມທີ 2')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('secondary_button_link')
                            ->label('ລິ້ງປຸ່ມທີ 2')
                            ->maxLength(255)
                            ->default('/about-us'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('ລຳດັບການສະແດງ')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('ເປີດການສະແດງຜົນ')
                            ->helperText('Hero ທີ່ເປີດໃຊ້ທັງໝົດຈະສະແດງເປັນສະໄລ໌ໝູນວຽນຢູ່ໜ້າຫຼັກ ຕາມລຳດັບການສະແດງ')
                            ->required()
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('ຮູບພື້ນຫຼັງ')
                    ->disk('public')
                    ->size(80),
                Tables\Columns\TextColumn::make('title_line1')
                    ->label('ຫົວຂໍ້')
                    ->searchable(),
                Tables\Columns\TextColumn::make('badge_text')
                    ->label('ປ້າຍ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ລຳດັບ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('ເປີດການສະແດງຜົນ')
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
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('ສະຖານະການສະແດງຜົນ'),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHeroes::route('/'),
            'create' => Pages\CreateHero::route('/create'),
            'edit' => Pages\EditHero::route('/{record}/edit'),
        ];
    }
}
