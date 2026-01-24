<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\PegawaiPanelProvider::class,
    App\Providers\Filament\PelangganPanelProvider::class,
    
    // Internal Staff Panels
    App\Providers\Filament\AdminPelayananPanelProvider::class,
    App\Providers\Filament\UnitSurveyPanelProvider::class,
    App\Providers\Filament\UnitPerencanaanPanelProvider::class,
    App\Providers\Filament\UnitKonstruksiPanelProvider::class,
    App\Providers\Filament\UnitTePanelProvider::class,
    App\Providers\Filament\SupervisorPanelProvider::class,
];
