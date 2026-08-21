<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrgDepartmentResource\Pages;
use App\Filament\Resources\OrgDepartmentResource\RelationManagers;
use App\Filament\Support\MaterialSymbolIcons;
use App\Models\OrgDepartment;
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

class OrgDepartmentResource extends Resource
{
    protected static ?string $model = OrgDepartment::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'ອົງກອນ';

    protected static ?string $navigationLabel = 'ໂຄງສ້າງອົງກອນ (Departments)';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('ຊື່ພະແນກ')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('role_label')
                            ->label('ບົດບາດ/ໜ້າທີ່')
                            ->maxLength(100)
                            ->default(null),
                        Forms\Components\Select::make('parent_id')
                            ->label('ພະແນກແມ່')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->default(null),
                        Forms\Components\Select::make('icon')
                            ->label('ໄອຄອນ')
                            ->options(function (?OrgDepartment $record, Get $get) {
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
                                    ->helperText('ໃສ່ຊື່ໄອຄອນຈາກ Google Material Symbols, ຕົວຢ່າງ: emoji_events')
                                    ->required(),
                            ])
                            ->createOptionUsing(fn (array $data) => $data['name'])
                            ->hint(fn (Get $get) => filled($get('icon'))
                                ? new HtmlString('<span class="material-symbols-outlined align-middle text-2xl">'.e($get('icon')).'</span>')
                                : null)
                            ->default(null),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('ຊື່ພະແນກ')
                    ->searchable()
                    ->description(fn (OrgDepartment $record): string => $record->role_label ?? ''),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('ພະແນກແມ່')
                    ->badge()
                    ->color('gray')
                    ->placeholder('— ພະແນກຫຼັກ —')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ລຳດັບ')
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
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('ພະແນກແມ່')
                    ->relationship('parent', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (OrgDepartment $record) {
                        if ($record->children()->exists() || $record->staffMembers()->exists()) {
                            Notification::make()
                                ->title('ບໍ່ສາມາດລຶບໄດ້')
                                ->body('ພະແນກນີ້ຍັງມີພະແນກຍ່ອຍ ຫຼື ບຸກຄະລາກອນຜູກຢູ່ ກະລຸນາຍ້າຍ ຫຼື ລຶບຂໍ້ມູນພາຍໃນອອກກ່ອນ')
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
                            if ($records->contains(fn (OrgDepartment $record) => $record->children()->exists() || $record->staffMembers()->exists())) {
                                Notification::make()
                                    ->title('ບໍ່ສາມາດລຶບໄດ້')
                                    ->body('ມີບາງພະແນກທີ່ຍັງມີພະແນກຍ່ອຍ ຫຼື ບຸກຄະລາກອນຜູກຢູ່')
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
            'index' => Pages\ListOrgDepartments::route('/'),
            'create' => Pages\CreateOrgDepartment::route('/create'),
            'edit' => Pages\EditOrgDepartment::route('/{record}/edit'),
        ];
    }
}
