<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewFrontend')
                ->label('ເບິ່ງໜ້າເວັບ (Frontend)')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(route('students'), shouldOpenInNewTab: true),
            Actions\CreateAction::make(),
        ];
    }
}
