<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Application extends Model
{
    protected $fillable = [
        'code_student',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'phone',
        'email',
        'village',
        'district',
        'province',
        'previous_school',
        'accommodation_type',
        'major_id',
        'academic_year_id',
        'photo',
        'certificate',
        'transcript',
        'status',
        'is_public',
        'source_page',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'is_public' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Application $application) {
            if (empty($application->code_student)) {
                $application->code_student = static::generateCodeStudent();
            }
        });
    }

    public static function generateCodeStudent(): string
    {
        do {
            $code = now()->format('dmY').str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (static::where('code_student', $code)->exists());

        return $code;
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'major_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::disk('public')->url($this->photo) : null;
    }

    public function getCertificateUrlAttribute(): ?string
    {
        return $this->certificate ? Storage::disk('public')->url($this->certificate) : null;
    }

    public function getTranscriptUrlAttribute(): ?string
    {
        return $this->transcript ? Storage::disk('public')->url($this->transcript) : null;
    }
}
