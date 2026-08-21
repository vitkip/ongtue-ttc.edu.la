<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentBlockResource\Pages;
use App\Filament\Resources\ContentBlockResource\RelationManagers;
use App\Filament\Support\MaterialSymbolIcons;
use App\Models\ContentBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ContentBlockResource extends Resource
{
    protected static ?string $model = ContentBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'ເນື້ອຫາເວັບໄຊ';

    protected static ?string $navigationLabel = 'ບລັອກເນື້ອຫາ (Content Blocks)';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('block_group')
                            ->label('ກຸ່ມ')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('icon')
                            ->label('ໄອຄອນ')
                            ->options(function (?ContentBlock $record, Get $get) {
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
                            ->default(null),
                        Forms\Components\Select::make('color')
                            ->label('ສີ')
                            ->options([
                                'saffron' => 'ສີເຫຼືອງ (Saffron)',
                                'maroon' => 'ສີແດງເລືອດໝູ (Maroon)',
                            ])
                            ->native(false)
                            ->default(null),
                        Forms\Components\TextInput::make('title')
                            ->label('ຫົວຂໍ້')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('ລາຍລະອຽດ')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('value')
                            ->label('ຄ່າ')
                            ->maxLength(50)
                            ->default(null),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('ລຳດັບການສະແດງ')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('ເປີດການສະແດງຜົນ')
                            ->required()
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('block_group')
                    ->label('ກຸ່ມ')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('ຫົວຂໍ້')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('ສີ')
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'maroon' ? 'danger' : 'warning'),
                Tables\Columns\TextColumn::make('value')
                    ->label('ຄ່າ')
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
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('block_group')
                    ->label('ກຸ່ມ')
                    ->options(fn (): array => ContentBlock::query()
                        ->distinct()
                        ->pluck('block_group', 'block_group')
                        ->all()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('ສະຖານະການສະແດງຜົນ'),
            ])
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
            'index' => Pages\ListContentBlocks::route('/'),
            'create' => Pages\CreateContentBlock::route('/create'),
            'edit' => Pages\EditContentBlock::route('/{record}/edit'),
        ];
    }
}
