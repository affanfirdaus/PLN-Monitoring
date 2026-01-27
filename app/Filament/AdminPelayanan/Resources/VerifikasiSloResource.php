<?php

namespace App\Filament\AdminPelayanan\Resources;

use App\Filament\AdminPelayanan\Resources\VerifikasiSloResource\Pages;
use App\Models\ServiceRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VerifikasiSloResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Verifikasi SLO';
    protected static ?int $navigationSort = 4;

    // Optional: Filter only records needing SLO verification
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->where('status', 'waiting_slo_verification');
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_registrasi')->disabled(),
                Forms\Components\TextInput::make('no_slo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_registrasi'),
                Tables\Columns\TextColumn::make('no_slo')->label('Nomor SLO'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerifikasiSlos::route('/'),
            'create' => Pages\CreateVerifikasiSlo::route('/create'),
            'edit' => Pages\EditVerifikasiSlo::route('/{record}/edit'),
        ];
    }
}
