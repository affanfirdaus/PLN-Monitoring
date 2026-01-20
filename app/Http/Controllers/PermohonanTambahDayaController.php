<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermohonanTambahDayaController extends Controller
{
    public function index()
    {
        // Redirect to the actual Wizard Step 1 route
        // This ensures session initialization in TambahDayaController runs correctly
        return redirect()->route('tambah-daya.step1');
    }
}
