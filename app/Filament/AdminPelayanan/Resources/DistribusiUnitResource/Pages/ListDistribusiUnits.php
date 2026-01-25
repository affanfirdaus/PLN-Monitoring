<?php

namespace App\Filament\AdminPelayanan\Resources\DistribusiUnitResource\Pages;

use App\Filament\AdminPelayanan\Resources\DistribusiUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDistribusiUnits extends ListRecords
{
    protected static string $resource = DistribusiUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
