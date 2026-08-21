<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Course $record) {
                    if ($record->applications()->exists()) {
                        Notification::make()
                            ->title('ບໍ່ສາມາດລຶບໄດ້')
                            ->body('ຫຼັກສູດນີ້ຍັງມີໃບສະໝັກຜູກຢູ່')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
        ];
    }
}
