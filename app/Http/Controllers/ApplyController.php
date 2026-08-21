<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Support\ApplicationOptions;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApplyController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $toOptions = fn (array $assoc) => collect($assoc)
            ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
            ->values();

        return Inertia::render('Apply', [
            'majors' => Course::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'title'])
                ->map(fn (Course $course) => ['value' => $course->id, 'label' => $course->title]),
            'academicYears' => AcademicYear::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'label'])
                ->map(fn (AcademicYear $year) => ['value' => $year->id, 'label' => $year->label]),
            'genderOptions' => $toOptions(ApplicationOptions::genders()),
            'accommodationOptions' => $toOptions(ApplicationOptions::accommodationTypes()),
            'provinceOptions' => $toOptions(ApplicationOptions::provinces()),
            'selectedMajorId' => $request->integer('major') ?: null,
        ]);
    }
}
