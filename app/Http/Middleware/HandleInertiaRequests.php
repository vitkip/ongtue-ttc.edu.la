<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $general = SiteSetting::group('general');

        $siteSettings = [
            'collegeNameLo' => $general['college_name_lo'] ?? 'ວິທະຍາໄລຄູສົງ ອົງຕື້',
            'collegeNameEn' => $general['college_name_en'] ?? 'ONG TUE BUDDHIST SANGHA COLLEGE',
            'logoUrl' => ! empty($general['logo']) ? Storage::disk('public')->url($general['logo']) : null,
            'footerAddress' => $general['footer_address'] ?? 'ຖະໜົນເສດຖາທິລາດ, ບ້ານວັດຈັນ, ນະຄອນຫຼວງວຽງຈັນ',
            'footerPhone' => $general['footer_phone'] ?? '+856 21 212 212',
            'footerEmail' => $general['footer_email'] ?? 'info@ongtuecollege.edu.la',
            'footerQuickLinks' => ! empty($general['footer_quick_links'])
                ? json_decode($general['footer_quick_links'], true)
                : [
                    ['label' => 'ຫຼັກສູດປະລິນຍາຕີ', 'url' => '/courses'],
                    ['label' => 'ກິດຈະກຳ ແລະ ງານສຳຄັນ', 'url' => '/events'],
                    ['label' => 'ກ່ຽວກັບພວກເຮົາ', 'url' => '/about-us'],
                    ['label' => 'ຕິດຕໍ່ເຮົາ', 'url' => '/contact'],
                ],
            'footerSocialLinks' => ! empty($general['footer_social_links'])
                ? json_decode($general['footer_social_links'], true)
                : [],
        ];

        View::share('siteName', $siteSettings['collegeNameLo']);
        View::share('metaTitle', $general['meta_title'] ?? $siteSettings['collegeNameLo']);
        View::share('metaDescription', $general['meta_description'] ?? '');
        View::share('metaKeywords', $general['meta_keywords'] ?? '');
        View::share('faviconUrl', $siteSettings['logoUrl']);

        return [
            ...parent::share($request),
            'siteSettings' => $siteSettings,
        ];
    }
}
