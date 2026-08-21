<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Event;
use App\Models\GalleryImage;
use App\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('ຄຳຮ້ອງໃໝ່', Inquiry::query()->where('status', 'new')->count())
                ->description('ລໍຖ້າການຕິດຕໍ່')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('warning')
                ->url(route('filament.admin.resources.inquiries.index', ['tableFilters[status][value]' => 'new'])),

            Stat::make('ຫຼັກສູດທັງໝົດ', Course::query()->count())
                ->description(Course::query()->where('is_active', true)->count().' ກຳລັງເປີດຮັບ')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('ກິດຈະກຳທີ່ຈະມາເຖິງ', Event::query()->where('event_date', '>=', now()->toDateString())->count())
                ->description('ຕາຕະລາງຂ້າງໜ້າ')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('ຮູບພາບໃນຄັງ', GalleryImage::query()->count())
                ->description(GalleryImage::query()->where('is_active', true)->count().' ກຳລັງສະແດງຢູ່ໜ້າເວັບ')
                ->descriptionIcon('heroicon-m-photo')
                ->color('gray'),
        ];
    }
}
