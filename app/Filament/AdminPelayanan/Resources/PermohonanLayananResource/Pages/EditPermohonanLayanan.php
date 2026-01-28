<?php

namespace App\Filament\AdminPelayanan\Resources\PermohonanLayananResource\Pages;

use App\Enums\PermohonanDetailStatus;
use App\Enums\PermohonanStatus;
use App\Filament\AdminPelayanan\Resources\PermohonanLayananResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPermohonanLayanan extends EditRecord
{
    protected static string $resource = PermohonanLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // STATUS: DITERIMA_PLN
            Action::make('mulaiVerifikasi')
                ->label('Mulai Verifikasi')
                ->color('primary')
                ->visible(fn () => $this->record->status === PermohonanStatus::DITERIMA_PLN)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::VERIFIKASI_SLO,
                    PermohonanDetailStatus::MENUNGGU_VERIFIKASI
                )),

            // STATUS: VERIFIKASI_SLO
            Action::make('verifikasiSloValid')
                ->label('SLO Valid')
                ->color('success')
                ->visible(fn () => $this->record->status === PermohonanStatus::VERIFIKASI_SLO)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::VERIFIKASI_SLO,
                    PermohonanDetailStatus::SLO_VALID
                )),
            
            Action::make('verifikasiSloInvalid')
                ->label('Dokumen Tidak Valid')
                ->color('danger')
                ->visible(fn () => $this->record->status === PermohonanStatus::VERIFIKASI_SLO)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::VERIFIKASI_SLO,
                    PermohonanDetailStatus::DOKUMEN_TIDAK_VALID
                )),

            Action::make('lanjutSurvey')
                ->label('Lanjut ke Survey')
                ->color('primary')
                ->visible(fn () => $this->record->status === PermohonanStatus::VERIFIKASI_SLO && 
                                   $this->record->status_detail === PermohonanDetailStatus::SLO_VALID)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::SURVEY_LAPANGAN,
                    PermohonanDetailStatus::SURVEY_BARU
                )),

            // STATUS: SURVEY_LAPANGAN
            Action::make('jadwalSurvey')
                ->label('Jadwalkan Survey')
                ->color('info')
                ->visible(fn () => $this->record->status === PermohonanStatus::SURVEY_LAPANGAN)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::SURVEY_LAPANGAN,
                    PermohonanDetailStatus::SURVEY_DIJADWALKAN
                )),
            
            Action::make('surveySelesai')
                ->label('Survey Selesai')
                ->color('success')
                ->visible(fn () => $this->record->status === PermohonanStatus::SURVEY_LAPANGAN)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::SURVEY_LAPANGAN,
                    PermohonanDetailStatus::SURVEY_SELESAI
                )),

            Action::make('lanjutMaterial')
                ->label('Lanjut Perencanaan')
                ->color('primary')
                ->visible(fn () => $this->record->status === PermohonanStatus::SURVEY_LAPANGAN && 
                                   $this->record->status_detail === PermohonanDetailStatus::SURVEY_SELESAI)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::PERENCANAAN_MATERIAL,
                    PermohonanDetailStatus::MATERIAL_ANALISA
                )),

            // STATUS: PERENCANAAN_MATERIAL
            Action::make('materialTersedia')
                ->label('Material Tersedia')
                ->color('success')
                ->visible(fn () => $this->record->status === PermohonanStatus::PERENCANAAN_MATERIAL)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::PERENCANAAN_MATERIAL,
                    PermohonanDetailStatus::MATERIAL_TERSEDIA
                )),
            
            Action::make('terbitkanTagihan')
                ->label('Terbitkan Tagihan')
                ->color('primary')
                ->visible(fn () => $this->record->status === PermohonanStatus::PERENCANAAN_MATERIAL)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::MENUNGGU_PEMBAYARAN,
                    PermohonanDetailStatus::TAGIHAN_TERBIT
                )),

            // STATUS: MENUNGGU_PEMBAYARAN
            Action::make('konfirmasiBayar')
                ->label('Konfirmasi Pembayaran')
                ->color('success')
                ->visible(fn () => $this->record->status === PermohonanStatus::MENUNGGU_PEMBAYARAN)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::MENUNGGU_PEMBAYARAN,
                    PermohonanDetailStatus::PEMBAYARAN_SELESAI
                )),

            Action::make('lanjutKonstruksi')
                ->label('Mulai Konstruksi')
                ->color('primary')
                ->visible(fn () => $this->record->status === PermohonanStatus::MENUNGGU_PEMBAYARAN && 
                                   $this->record->status_detail === PermohonanDetailStatus::PEMBAYARAN_SELESAI)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::KONSTRUKSI_INSTALASI,
                    PermohonanDetailStatus::KONSTRUKSI_JARINGAN
                )),

            // STATUS: KONSTRUKSI_INSTALASI
            Action::make('updateProgress')
                ->label('Update Progress')
                ->color('info')
                ->visible(fn () => $this->record->status === PermohonanStatus::KONSTRUKSI_INSTALASI)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::KONSTRUKSI_INSTALASI,
                    PermohonanDetailStatus::KONSTRUKSI_PROGRESS
                )),
            
             Action::make('instalasiPelanggan')
                ->label('Instalasi Pelanggan')
                ->color('info')
                ->visible(fn () => $this->record->status === PermohonanStatus::KONSTRUKSI_INSTALASI)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::KONSTRUKSI_INSTALASI,
                    PermohonanDetailStatus::INSTALASI_PELANGGAN
                )),

            Action::make('lanjutPenyalaan')
                ->label('Lanjut Penyalaan')
                ->color('primary')
                ->visible(fn () => $this->record->status === PermohonanStatus::KONSTRUKSI_INSTALASI)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::PENYALAAN_TE,
                    PermohonanDetailStatus::PENYALAAN_BERHASIL
                )),

            // STATUS: PENYALAAN_TE
            Action::make('konfirmasiNyala')
                ->label('Konfirmasi Nyala')
                ->color('success')
                ->visible(fn () => $this->record->status === PermohonanStatus::PENYALAAN_TE)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::PENYALAAN_TE,
                    PermohonanDetailStatus::KONFIRMASI_NYALA
                )),

            Action::make('selesaikanPermohonan')
                ->label('Selesaikan Permohonan')
                ->color('primary')
                ->visible(fn () => $this->record->status === PermohonanStatus::PENYALAAN_TE && 
                                    $this->record->status_detail === PermohonanDetailStatus::KONFIRMASI_NYALA)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::SELESAI,
                    PermohonanDetailStatus::ADMINISTRASI_AKHIR
                )),

             // STATUS: SELESAI
             Action::make('finishPermohonan')
                ->label('Finish')
                ->color('success')
                ->visible(fn () => $this->record->status === PermohonanStatus::SELESAI && $this->record->status_detail !== PermohonanDetailStatus::FINISH)
                ->action(fn () => $this->record->transitionTo(
                    PermohonanStatus::SELESAI,
                    PermohonanDetailStatus::FINISH
                )),
        ];
    }
}
