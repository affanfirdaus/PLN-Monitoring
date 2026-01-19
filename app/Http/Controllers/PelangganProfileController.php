<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganProfileController extends Controller
{
    public function show()
    {
        return view('pelanggan.profil', [
            'user' => Auth::user()
        ]);
    }
}
