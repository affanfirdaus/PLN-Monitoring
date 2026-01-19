<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiUnitController extends Controller
{
    public function index()
    {
        return view('pegawai.login-unit');
    }
}
