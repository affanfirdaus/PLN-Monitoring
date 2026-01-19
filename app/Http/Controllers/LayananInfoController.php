<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayananInfoController extends Controller
{
    public function tambahDaya()
    {
        return view('layanan.tambah-daya-info');
    }

    public function pasangBaru()
    {
        return view('layanan.pasang-baru-info');
    }
}
