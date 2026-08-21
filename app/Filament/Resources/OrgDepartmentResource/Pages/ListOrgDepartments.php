<?php

namespace App\Filament\Resources\OrgDepartmentResource\Pages;

use App\Filament\Resources\OrgDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrgDepartments extends ListRecords
{
    protected static string $resource = OrgDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
