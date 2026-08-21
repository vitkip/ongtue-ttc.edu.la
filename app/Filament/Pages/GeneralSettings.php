<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class GeneralSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'ລະບົບ';

    protected static ?string $navigationLabel = 'ຕັ້ງຄ່າທົ່ວໄປ (General)';

    protected static ?int $navigationSort = -1;

    protected static string $view = 'filament.pages.general-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::group('general');

        $this->form->fill([
            'college_name_lo' => $settings['college_name_lo'] ?? 'ວິທະຍາໄລຄູສົງ ອົງຕື້',
            'college_name_en' => $settings['college_name_en'] ?? 'ONG TUE BUDDHIST SANGHA COLLEGE',
            'logo' => $settings['logo'] ?? null,
            'footer_address' => $settings['footer_address'] ?? 'ຖະໜົນເສດຖາທິລາດ, ບ້ານວັດຈັນ, ນະຄອນຫຼວງວຽງຈັນ',
            'footer_phone' => $settings['footer_phone'] ?? '+856 21 212 212',
            'footer_email' => $settings['footer_email'] ?? 'info@ongtuecollege.edu.la',
            'footer_quick_links' => ! empty($settings['footer_quick_links'])
                ? json_decode($settings['footer_quick_links'], true)
                : self::defaultQuickLinks(),
            'footer_social_links' => ! empty($settings['footer_social_links'])
                ? json_decode($settings['footer_social_links'], true)
                : [],
        ]);
    }

    protected static function defaultQuickLinks(): array
    {
        return [
            ['label' => 'ຫຼັກສູດປະລິນຍາຕີ', 'url' => '/courses'],
            ['label' => 'ກິດຈະກຳ ແລະ ງານສຳຄັນ', 'url' => '/events'],
            ['label' => 'ກ່ຽວກັບພວກເຮົາ', 'url' => '/about-us'],
            ['label' => 'ຕິດຕໍ່ເຮົາ', 'url' => '/contact'],
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ຊື່ວິທະຍາໄລ')
                    ->description('ຊື່ນີ້ຈະສະແດງຢູ່ header, footer ແລະ ຫົວຂໍ້ໜ້າເວັບ')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('college_name_lo')
                            ->label('ຊື່ວິທະຍາໄລ (ພາສາລາວ)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('college_name_en')
                            ->label('ຊື່ວິທະຍາໄລ (ພາສາອັງກິດ)')
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('ໂລໂກ້')
                    ->description('ຮູບໂລໂກ້ຈະສະແດງຢູ່ header ຂອງໜ້າເວັບ ແລະ ແຜງຄວບຄຸມ')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('ໂລໂກ້')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->imagePreviewHeight('150')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('ຂໍ້ມູນຕິດຕໍ່ (Footer)')
                    ->description('ຂໍ້ມູນນີ້ຈະສະແດງຢູ່ໃນສ່ວນ CONTACT INFO ຂອງ footer')
                    ->schema([
                        Forms\Components\Textarea::make('footer_address')
                            ->label('ທີ່ຢູ່')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('footer_phone')
                            ->label('ເບີໂທ')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('footer_email')
                            ->label('ອີເມວ')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('ລິ້ງດ່ວນ (Quick Links)')
                    ->description('ລິ້ງທີ່ຈະສະແດງຢູ່ໃນສ່ວນ QUICK LINKS ຂອງ footer')
                    ->schema([
                        Forms\Components\Repeater::make('footer_quick_links')
                            ->label('ລິ້ງດ່ວນ')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('ປ້າຍຊື່')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('url')
                                    ->label('ລິ້ງ (ຕົວຢ່າງ: /courses)')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->addActionLabel('ເພີ່ມລິ້ງດ່ວນ')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('ໂຊເຊียລມີເດຍ (Follow Us)')
                    ->description('ລິ້ງທີ່ຈະສະແດງຢູ່ໃນສ່ວນ FOLLOW US ຂອງ footer')
                    ->schema([
                        Forms\Components\Repeater::make('footer_social_links')
                            ->label('ລິ້ງໂຊເຊียລມີເດຍ')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\Select::make('platform')
                                    ->label('ແພລດຟອມ')
                                    ->options([
                                        'facebook' => 'Facebook',
                                        'youtube' => 'YouTube',
                                        'tiktok' => 'TikTok',
                                        'instagram' => 'Instagram',
                                        'twitter' => 'Twitter / X',
                                        'telegram' => 'Telegram',
                                        'whatsapp' => 'WhatsApp',
                                        'line' => 'Line',
                                        'other' => 'ອື່ນໆ (Other)',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('url')
                                    ->label('ລິ້ງ (URL)')
                                    ->url()
                                    ->required()
                                    ->maxLength(500),
                            ])
                            ->columns(2)
                            ->addActionLabel('ເພີ່ມລິ້ງໂຊເຊียລມີເດຍ')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['platform'] ?? null)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $fields = [
            'college_name_lo' => ['type' => 'text', 'label' => 'ຊື່ວິທະຍາໄລ (ພາສາລາວ)'],
            'college_name_en' => ['type' => 'text', 'label' => 'ຊື່ວິທະຍາໄລ (ພາສາອັງກິດ)'],
            'logo' => ['type' => 'image', 'label' => 'ໂລໂກ້'],
            'footer_address' => ['type' => 'textarea', 'label' => 'ທີ່ຢູ່ (Footer)'],
            'footer_phone' => ['type' => 'text', 'label' => 'ເບີໂທ (Footer)'],
            'footer_email' => ['type' => 'text', 'label' => 'ອີເມວ (Footer)'],
            'footer_quick_links' => ['type' => 'json', 'label' => 'ລິ້ງດ່ວນ (Footer)'],
            'footer_social_links' => ['type' => 'json', 'label' => 'ລິ້ງໂຊເຊියລມີເດຍ (Footer)'],
        ];

        foreach ($fields as $key => $meta) {
            $value = $data[$key] ?? null;

            if ($meta['type'] === 'json') {
                $value = json_encode($value ?? []);
            }

            SiteSetting::updateOrCreate(
                ['setting_key' => $key],
                [
                    'setting_value' => $value,
                    'setting_type' => $meta['type'],
                    'group_name' => 'general',
                    'label' => $meta['label'],
                ]
            );
        }

        Notification::make()
            ->title('ບັນທຶກການຕັ້ງຄ່າສຳເລັດແລ້ວ')
            ->success()
            ->send();
    }
}
