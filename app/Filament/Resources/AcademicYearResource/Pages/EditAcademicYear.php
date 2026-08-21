<?php

namespace App\Filament\Resources\AcademicYearResource\Pages;

use App\Filament\Resources\AcademicYearResource;
use App\Models\AcademicYear;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditAcademicYear extends EditRecord
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (AcademicYear $record) {
                    if ($record->applications()->exists()) {
                        Notification::make()
                            ->title('ບໍ່ສາມາດລຶບໄດ້')
                            ->body('ປີການສຶກສານີ້ຍັງມີໃບສະໝັກຜູກຢູ່')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
        ];
    }
}
