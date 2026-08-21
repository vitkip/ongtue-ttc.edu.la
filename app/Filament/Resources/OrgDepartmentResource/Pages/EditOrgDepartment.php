<?php

namespace App\Filament\Resources\OrgDepartmentResource\Pages;

use App\Filament\Resources\OrgDepartmentResource;
use App\Models\OrgDepartment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditOrgDepartment extends EditRecord
{
    protected static string $resource = OrgDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (OrgDepartment $record) {
                    if ($record->children()->exists() || $record->staffMembers()->exists()) {
                        Notification::make()
                            ->title('ບໍ່ສາມາດລຶບໄດ້')
                            ->body('ພະແນກນີ້ຍັງມີພະແນກຍ່ອຍ ຫຼື ບຸກຄະລາກອນຜູກຢູ່ ກະລຸນາຍ້າຍ ຫຼື ລຶບຂໍ້ມູນພາຍໃນອອກກ່ອນ')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
        ];
    }
}
