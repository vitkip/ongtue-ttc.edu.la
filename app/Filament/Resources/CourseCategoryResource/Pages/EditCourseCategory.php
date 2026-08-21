<?php

namespace App\Filament\Resources\CourseCategoryResource\Pages;

use App\Filament\Resources\CourseCategoryResource;
use App\Models\CourseCategory;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditCourseCategory extends EditRecord
{
    protected static string $resource = CourseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (CourseCategory $record) {
                    if ($record->courses()->exists()) {
                        Notification::make()
                            ->title('ບໍ່ສາມາດລຶບໄດ້')
                            ->body('ໝວດໝູ່ນີ້ຍັງມີຫຼັກສູດຜູກຢູ່ ກະລຸນາຍ້າຍ ຫຼື ລຶບຫຼັກສູດອອກກ່ອນ')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
        ];
    }
}
