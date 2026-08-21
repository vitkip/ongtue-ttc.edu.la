<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'ເນື້ອຫາເວັບໄຊ';

    protected static ?string $navigationLabel = 'ກິດຈະກຳ (Events)';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ຂໍ້ມູນກິດຈະກຳ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->label('ໝວດໝູ່')
                            ->options([
                                'religious' => 'ພິທີທາງສາສະໜາ',
                                'academic' => 'ສຳມະນາວິຊາການ',
                                'social' => 'ກິດຈະກຳເພື່ອສັງຄົມ',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('badge_label')
                            ->label('ປ້າຍກຳກັບ')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('title')
                            ->label('ຫົວຂໍ້ກິດຈະກຳ')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('ລາຍລະອຽດ')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('ວັນ ເວລາ ແລະ ສະຖານທີ່')
                    ->columns(3)
                    ->schema([
                        Forms\Components\DatePicker::make('event_date')
                            ->label('ວັນທີ')
                            ->required(),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('ເວລາເລີ່ມ'),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('ເວລາສິ້ນສຸດ'),
                        Forms\Components\TextInput::make('location')
                            ->label('ສະຖານທີ່')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('registration_url')
                            ->label('ລິ້ງລົງທະບຽນ (ພາຍນອກ)')
                            ->helperText('ຕົວຢ່າງ: ລິ້ງ Google Form. ຖ້າປະໄວ້ວ່າງ ປຸ່ມ "ລົງທະບຽນ" ຈະເປີດຟອມສະໝັກຮຽນຂອງວິທະຍາໄລແທນ')
                            ->url()
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('ຮູບພາບ ແລະ ການສະແດງຜົນ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image_url')
                            ->label('ຮູບພາບ')
                            ->image()
                            ->disk('public')
                            ->directory('events')
                            ->imagePreviewHeight('250')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('ກິດຈະກຳແນະນຳ')
                            ->helperText('ສະແດງເປັນຈຸດເດັ່ນຢູ່ໜ້າຫຼັກ')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('ເປີດການສະແດງຜົນ')
                            ->helperText('ປິດໄວ້ຈະບໍ່ສະແດງຢູ່ໜ້າເວັບ')
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
                    ->label('')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('ຫົວຂໍ້')
                    ->searchable()
                    ->description(fn (Event $record): string => $record->location ?? ''),
                Tables\Columns\TextColumn::make('category')
                    ->label('ໝວດໝູ່')
                    ->badge(),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('ວັນທີ')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('ເລີ່ມ')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('ສິ້ນສຸດ')
                    ->time('H:i'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('ແນະນຳ')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'religious' => 'ພິທີທາງສາສະໜາ',
                        'academic' => 'ສຳມະນາວິຊາການ',
                        'social' => 'ກິດຈະກຳເພື່ອສັງຄົມ',
                    ]),
                Tables\Filters\Filter::make('upcoming')
                    ->label('ຈະມາເຖິງເທົ່ານັ້ນ')
                    ->query(fn (Builder $query): Builder => $query->where('event_date', '>=', now()->toDateString()))
                    ->toggle(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('ສະຖານະການສະແດງຜົນ'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('ກິດຈະກຳແນະນຳ'),
            ])
            ->defaultSort('event_date')
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
