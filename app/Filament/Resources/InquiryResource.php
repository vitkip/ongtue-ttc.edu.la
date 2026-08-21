<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'ຄຳຮ້ອງ ແລະ ຕິດຕໍ່';

    protected static ?string $navigationLabel = 'ຄຳຮ້ອງ (Inquiries)';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ຂໍ້ມູນຜູ້ຕິດຕໍ່')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('full_name')->label('ຊື່ ແລະ ນາມສະກຸນ')->disabled(),
                        Forms\Components\TextInput::make('phone')->label('ເບີໂທ')->disabled(),
                        Forms\Components\TextInput::make('email')->label('ອີເມວ')->disabled(),
                        Forms\Components\TextInput::make('subject')->label('ຫົວຂໍ້')->disabled(),
                        Forms\Components\Textarea::make('message')->label('ຂໍ້ຄວາມ')->disabled()->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('ການຕິດຕາມ')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('ສະຖານະ')
                            ->options([
                                'new' => 'ໃໝ່ (New)',
                                'contacted' => 'ຕິດຕໍ່ແລ້ວ (Contacted)',
                                'closed' => 'ປິດແລ້ວ (Closed)',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('ຊື່')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (Inquiry $record): string => $record->phone ?? $record->email ?? ''),
                Tables\Columns\TextColumn::make('subject')
                    ->label('ຫົວຂໍ້')
                    ->limit(40)
                    ->searchable()
                    ->getStateUsing(fn (Inquiry $record): string => $record->subject ?: '—'),
                Tables\Columns\TextColumn::make('status')
                    ->label('ສະຖານະ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('ວັນທີສົ່ງ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('ສະຖານະ')
                    ->options(['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed']),
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
            ->emptyStateHeading('ຍັງບໍ່ມີຄຳຮ້ອງເຂົ້າມາ')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInquiries::route('/'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
