<?php

namespace App\Filament\AdminPelayanan\Resources\AkunPelangganResource\Pages;

use App\Filament\AdminPelayanan\Resources\AkunPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAkunPelanggans extends ListRecords
{
    protected static string $resource = AkunPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
