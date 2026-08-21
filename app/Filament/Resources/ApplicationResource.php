<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use App\Support\ApplicationOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'ຄຳຮ້ອງ ແລະ ຕິດຕໍ່';

    protected static ?string $navigationLabel = 'ໃບສະໝັກຮຽນ (Applications)';

    protected static ?string $recordTitleAttribute = 'code_student';

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
                Forms\Components\Section::make('ຂໍ້ມູນສ່ວນຕົວ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('code_student')->label('ລະຫັດນັກສຶກສາ')->disabled(),
                        Forms\Components\TextInput::make('first_name')->label('ຊື່')
                            ->required()->maxLength(100),
                        Forms\Components\TextInput::make('last_name')->label('ນາມສະກຸນ')
                            ->required()->maxLength(100),
                        Forms\Components\Select::make('gender')->label('ເພດ')
                            ->options(ApplicationOptions::genders())
                            ->required(),
                        Forms\Components\DatePicker::make('dob')->label('ວັນເດືອນປີເກີດ')
                            ->required()->maxDate(now()->subDay()),
                        Forms\Components\TextInput::make('phone')->label('ເບີໂທ')
                            ->tel()->maxLength(30),
                        Forms\Components\TextInput::make('email')->label('ອີເມວ')
                            ->email()->maxLength(150),
                        Forms\Components\TextInput::make('village')->label('ບ້ານ')->maxLength(100),
                        Forms\Components\TextInput::make('district')->label('ເມືອງ')->maxLength(100),
                        Forms\Components\Select::make('province')->label('ແຂວງ')
                            ->options(ApplicationOptions::provinces())
                            ->searchable(),
                    ]),
                Forms\Components\Section::make('ຂໍ້ມູນການສຶກສາ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('previous_school')->label('ຈົບມໍປາຍຈາກ')->maxLength(255),
                        Forms\Components\Select::make('accommodation_type')->label('ປະເພດທີ່ພັກ')
                            ->options(ApplicationOptions::accommodationTypes())
                            ->required(),
                    ]),
                Forms\Components\Section::make('ຂໍ້ມູນການລົງທະບຽນ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('major_id')
                            ->label('ສາຂາ')
                            ->relationship('major', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('academic_year_id')
                            ->label('ປີການສຶກສາ')
                            ->relationship('academicYear', 'label')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Forms\Components\Section::make('ເອກະສານ')
                    ->columns(3)
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->label('ຮູບພາບ')
                            ->image()
                            ->disk('public')
                            ->directory('applications/photos')
                            ->imagePreviewHeight('150')
                            ->openable()
                            ->required(),
                        Forms\Components\FileUpload::make('certificate')
                            ->label('ໃບຢັ້ງຢືນ')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'application/pdf'])
                            ->disk('public')
                            ->directory('applications/certificates')
                            ->imagePreviewHeight('150')
                            ->openable(),
                        Forms\Components\FileUpload::make('transcript')
                            ->label('ໃບຄະແນນ')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'application/pdf'])
                            ->disk('public')
                            ->directory('applications/transcripts')
                            ->imagePreviewHeight('150')
                            ->openable(),
                    ]),
                Forms\Components\Section::make('ການຕິດຕາມ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('ສະຖານະ')
                            ->options([
                                'new' => 'ໃໝ່ (New)',
                                'contacted' => 'ຕິດຕໍ່ແລ້ວ (Contacted)',
                                'closed' => 'ປິດແລ້ວ (Closed)',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_public')
                            ->label('ສະແດງໃນໜ້າເວັບ')
                            ->helperText('ອະນຸມັດໃຫ້ສະແດງຮູບ, ຊື່, ສາຂາ ແລະ ປີການສຶກສາຂອງນັກສຶກສາຄົນນີ້ຢູ່ໜ້າ "ນັກສຶກສາ" ສາທາລະນະ')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_student')
                    ->label('ລະຫັດ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('ຊື່')
                    ->weight('bold')
                    ->searchable(['first_name', 'last_name'])
                    ->getStateUsing(fn (Application $record): string => trim("{$record->first_name} {$record->last_name}"))
                    ->description(fn (Application $record): string => $record->phone ?? $record->email ?? ''),
                Tables\Columns\TextColumn::make('major.title')
                    ->label('ສາຂາ')
                    ->limit(30),
                Tables\Columns\TextColumn::make('academicYear.label')
                    ->label('ປີການສຶກສາ'),
                Tables\Columns\TextColumn::make('status')
                    ->label('ສະຖານະ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('ສະແດງໃນເວັບ')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('ວັນທີສະໝັກ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('major_id')
                    ->label('ສາຂາ')
                    ->relationship('major', 'title'),
                Tables\Filters\SelectFilter::make('academic_year_id')
                    ->label('ປີການສຶກສາ')
                    ->relationship('academicYear', 'label'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('ສະຖານະ')
                    ->options(['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed']),
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('ສະແດງໃນເວັບ'),
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
            ->emptyStateHeading('ຍັງບໍ່ມີໃບສະໝັກເຂົ້າມາ')
            ->emptyStateIcon('heroicon-o-identification');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
