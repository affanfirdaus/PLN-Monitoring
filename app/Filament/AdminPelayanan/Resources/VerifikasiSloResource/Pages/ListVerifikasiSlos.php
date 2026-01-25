<?php

namespace App\Filament\AdminPelayanan\Resources\VerifikasiSloResource\Pages;

use App\Filament\AdminPelayanan\Resources\VerifikasiSloResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiSlos extends ListRecords
{
    protected static string $resource = VerifikasiSloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
