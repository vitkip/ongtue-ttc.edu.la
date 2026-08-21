<?php

namespace App\Filament\Support;

class MaterialSymbolIcons
{
    /**
     * Curated Material Symbols (Outlined) icon names, grouped for the picker.
     * These are the same ligature names rendered by the public site via
     * the "material-symbols-outlined" font class.
     */
    public static function names(): array
    {
        return [
            'ທົ່ວໄປ' => [
                'home', 'apartment', 'business', 'domain', 'corporate_fare',
                'account_balance', 'account_tree', 'hub', 'dashboard', 'grid_view',
                'business_center', 'store',
            ],
            'ສາສະໜາ ແລະ ວັດທະນະທຳ' => [
                'temple_buddhist', 'self_improvement', 'spa', 'volunteer_activism',
                'diversity_3', 'history_edu', 'auto_awesome',
            ],
            'ການສຶກສາ' => [
                'school', 'menu_book', 'book', 'auto_stories', 'library_books', 'class',
                'science', 'calculate', 'psychology', 'translate', 'assignment',
                'assignment_ind', 'quiz', 'edit_note', 'grading', 'workspace_premium',
                'emoji_events', 'military_tech', 'draw',
            ],
            'ບຸກຄົນ ແລະ ອົງກອນ' => [
                'person', 'people', 'group', 'groups', 'groups_2', 'supervisor_account',
                'badge', 'admin_panel_settings', 'family_restroom', 'diversity_1',
                'diversity_2', 'elderly',
            ],
            'ການສື່ສານ' => [
                'mail', 'email', 'phone', 'call', 'contact_mail', 'contact_phone',
                'chat', 'forum', 'support_agent', 'campaign', 'newspaper', 'article',
                'description',
            ],
            'ວັນທີ ແລະ ກິດຈະກຳ' => [
                'event', 'event_note', 'calendar_month', 'calendar_today', 'schedule',
                'timer', 'alarm',
            ],
            'ສະຖານທີ່' => [
                'place', 'location_on', 'map', 'public', 'language',
            ],
            'ສື່ ແລະ ຮູບພາບ' => [
                'image', 'photo_library', 'collections', 'videocam', 'movie', 'mic',
                'palette', 'brush', 'theater_comedy', 'music_note',
            ],
            'ກິລາ' => [
                'sports_soccer', 'sports_basketball', 'sports_esports',
                'sports_volleyball', 'sports_tennis',
            ],
            'ເຕັກໂນໂລຊີ' => [
                'computer', 'laptop', 'code', 'terminal', 'storage', 'cloud',
                'security', 'lock',
            ],
            'ຄ່າຮຽນ ແລະ ທຶນ' => [
                'payments', 'card_giftcard', 'savings', 'account_balance_wallet',
                'redeem', 'volunteer_activism', 'paid',
            ],
            'ອື່ນໆ' => [
                'star', 'verified', 'favorite', 'handshake', 'info', 'help',
                'category', 'link', 'folder', 'folder_open', 'list_alt', 'view_list',
            ],
        ];
    }

    /**
     * Grouped options ready for a Filament Select (with allowHtml()),
     * each label showing a live glyph preview next to the icon name.
     */
    public static function options(): array
    {
        $groups = [];

        foreach (self::names() as $group => $icons) {
            foreach ($icons as $icon) {
                $groups[$group][$icon] = self::renderOption($icon);
            }
        }

        return $groups;
    }

    public static function exists(string $icon): bool
    {
        foreach (self::names() as $icons) {
            if (in_array($icon, $icons, true)) {
                return true;
            }
        }

        return false;
    }

    public static function renderOption(string $icon): string
    {
        $escaped = e($icon);

        return <<<HTML
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-base leading-none">{$escaped}</span>
                <span>{$escaped}</span>
            </span>
            HTML;
    }
}
