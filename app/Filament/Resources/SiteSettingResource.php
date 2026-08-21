<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'ລະບົບ';

    protected static ?string $navigationLabel = 'ຕັ້ງຄ່າເວັບໄຊ (Site Settings)';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('ປ້າຍຊື່')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('setting_key')
                            ->label('ຄີ (Key)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('setting_type')
                            ->label('ປະເພດຄ່າ')
                            ->options([
                                'text' => 'ຂໍ້ຄວາມ (Text)',
                                'textarea' => 'ຂໍ້ຄວາມຍາວ (Textarea)',
                                'number' => 'ຕົວເລກ (Number)',
                                'boolean' => 'ຈິງ/ບໍ່ຈິງ (Boolean)',
                                'url' => 'ລິ້ງ (URL)',
                            ])
                            ->required()
                            ->default('text'),
                        Forms\Components\TextInput::make('group_name')
                            ->label('ກຸ່ມ')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Textarea::make('setting_value')
                            ->label('ຄ່າ')
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('setting_key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('setting_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
