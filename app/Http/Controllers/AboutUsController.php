<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use App\Models\GalleryImage;
use App\Models\OrgDepartment;
use Inertia\Inertia;
use Inertia\Response;

class AboutUsController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('AboutUs', [
            'stats' => ContentBlock::where('block_group', 'stat_counter')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'missionPillars' => ContentBlock::where('block_group', 'mission_pillar')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'orgRoot' => OrgDepartment::whereNull('parent_id')
                ->with(['children.staffMembers' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->first(),
            'gallery' => GalleryImage::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (GalleryImage $image) => [
                    ...$image->toArray(),
                    'image_url' => $image->resolved_image_url,
                ]),
        ]);
    }
}
