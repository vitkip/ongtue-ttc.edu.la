<?php

namespace App\Filament\Pages;

use App\Filament\Support\MaterialSymbolIcons;
use App\Models\ContentBlock;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ContentBlocks extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'ເນື້ອຫາເວັບໄຊ';

    protected static ?string $navigationLabel = 'ບລັອກເນື້ອຫາ (Content Blocks)';

    protected static string $view = 'filament.pages.content-blocks';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'home_feature' => $this->loadGroup('home_feature'),
            'mission_pillar' => $this->loadGroup('mission_pillar'),
            'stat_counter' => $this->loadGroup('stat_counter'),
        ]);
    }

    protected function loadGroup(string $group): array
    {
        return ContentBlock::where('block_group', $group)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (ContentBlock $block): array => [
                'id' => $block->id,
                'icon' => $block->icon,
                'color' => $block->color,
                'title' => $block->title,
                'description' => $block->description,
                'value' => $block->value,
                'is_active' => $block->is_active,
            ])
            ->all();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ຈຸດເດັ່ນ (ໜ້າຫຼັກ)')
                    ->description('ສະແດງຢູ່ໜ້າຫຼັກ ພາກ "ເປັນຫຍັງຕ້ອງເລືອກພວກເຮົາ" — ແນະນຳ 4 ບລັອກ')
                    ->schema([
                        Forms\Components\Repeater::make('home_feature')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\Hidden::make('id'),
                                $this->iconField(),
                                Forms\Components\Select::make('color')
                                    ->label('ສີ')
                                    ->options([
                                        'saffron' => 'ສີເຫຼືອງ (Saffron)',
                                        'maroon' => 'ສີແດງເລືອດໝູ (Maroon)',
                                    ])
                                    ->native(false)
                                    ->placeholder('ຄ່າເລີ່ມຕົ້ນ'),
                                Forms\Components\TextInput::make('title')
                                    ->label('ຫົວຂໍ້')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('description')
                                    ->label('ລາຍລະອຽດ')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('ເປີດການສະແດງຜົນ')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->addActionLabel('ເພີ່ມບລັອກ')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),

                Forms\Components\Section::make('ພັນທະກິດ (ກ່ຽວກັບພວກເຮົາ)')
                    ->description('ສະແດງຢູ່ໜ້າ "ກ່ຽວກັບພວກເຮົາ" ພາກພັນທະກິດ — ແນະນຳ 4 ບລັອກ')
                    ->schema([
                        Forms\Components\Repeater::make('mission_pillar')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\Hidden::make('id'),
                                $this->iconField(),
                                Forms\Components\TextInput::make('title')
                                    ->label('ຫົວຂໍ້')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->label('ລາຍລະອຽດ')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('ເປີດການສະແດງຜົນ')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->addActionLabel('ເພີ່ມບລັອກ')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),

                Forms\Components\Section::make('ຕົວເລກສະຖິຕິ (ກ່ຽວກັບພວກເຮົາ)')
                    ->description('ສະແດງຢູ່ໜ້າ "ກ່ຽວກັບພວກເຮົາ" ພາກຕົວເລກສະຖິຕິ — ໃສ່ຕົວເລກ ແລະ ຄຳອະທິບາຍ')
                    ->schema([
                        Forms\Components\Repeater::make('stat_counter')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\Hidden::make('id'),
                                Forms\Components\TextInput::make('value')
                                    ->label('ຕົວເລກ (ຕົວຢ່າງ: 1995, +5000)')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('title')
                                    ->label('ຄຳອະທິບາຍ')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('ເປີດການສະແດງຜົນ')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->addActionLabel('ເພີ່ມຕົວເລກ')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),
            ])
            ->statePath('data');
    }

    protected function iconField(): Forms\Components\Select
    {
        return Forms\Components\Select::make('icon')
            ->label('ໄອຄອນ')
            ->options($this->iconOptions())
            ->searchable()
            ->allowHtml()
            ->native(false)
            ->placeholder('ເລືອກໄອຄອນ...');
    }

    protected function iconOptions(): array
    {
        $options = MaterialSymbolIcons::options();

        $extra = [];

        foreach (ContentBlock::query()->pluck('icon')->filter()->unique() as $icon) {
            if (! MaterialSymbolIcons::exists($icon)) {
                $extra[$icon] = MaterialSymbolIcons::renderOption($icon);
            }
        }

        if ($extra !== []) {
            $options['ອື່ນໆ (ໃຊ້ຢູ່ແລ້ວ)'] = $extra;
        }

        return $options;
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->syncGroup('home_feature', $data['home_feature'] ?? [], ['icon', 'color', 'title', 'description']);
        $this->syncGroup('mission_pillar', $data['mission_pillar'] ?? [], ['icon', 'title', 'description']);
        $this->syncGroup('stat_counter', $data['stat_counter'] ?? [], ['value', 'title']);

        Notification::make()
            ->title('ບັນທຶກບລັອກເນື້ອຫາສຳເລັດແລ້ວ')
            ->success()
            ->send();
    }

    protected function syncGroup(string $group, array $items, array $fields): void
    {
        $keepIds = [];
        $order = 1;

        foreach ($items as $item) {
            $attributes = [
                'block_group' => $group,
                'sort_order' => $order++,
                'is_active' => (bool) ($item['is_active'] ?? true),
            ];

            foreach ($fields as $field) {
                $attributes[$field] = $item[$field] ?? null;
            }

            $record = ! empty($item['id'])
                ? ContentBlock::find($item['id'])
                : null;

            if ($record) {
                $record->update($attributes);
            } else {
                $record = ContentBlock::create($attributes);
            }

            $keepIds[] = $record->id;
        }

        ContentBlock::where('block_group', $group)
            ->whereNotIn('id', $keepIds)
            ->delete();
    }
}
