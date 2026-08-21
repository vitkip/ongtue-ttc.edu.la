<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Courses', [
            'courses' => Course::with('category')
                ->where('is_active', true)
                ->whereHas('category', fn ($query) => $query->where('is_active', true))
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Course $course) => [
                    ...$course->toArray(),
                    'category' => $course->category?->slug,
                    'image_url' => $course->resolved_image_url,
                ]),
            'categories' => CourseCategory::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug']),
        ]);
    }
}
