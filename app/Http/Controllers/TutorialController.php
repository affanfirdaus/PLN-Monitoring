<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function titikKoordinat()
    {
        return view('tutorial.titik-koordinat');
    }
}
