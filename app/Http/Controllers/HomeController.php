<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use App\Models\Course;
use App\Models\Hero;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Home', [
            'heroes' => Hero::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Hero $hero) => [
                    ...$hero->toArray(),
                    'image_url' => $hero->resolved_image_url,
                ]),
            'features' => ContentBlock::where('block_group', 'home_feature')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'featuredCourses' => Course::with('category')
                ->where('is_featured', true)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Course $course) => [
                    ...$course->toArray(),
                    'category' => $course->category?->slug,
                    'image_url' => $course->resolved_image_url,
                ]),
            'testimonials' => Testimonial::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Testimonial $testimonial) => [
                    ...$testimonial->toArray(),
                    'photo_url' => $testimonial->resolved_photo_url,
                ]),
        ]);
    }
}
