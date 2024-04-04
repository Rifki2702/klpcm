<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function __construct(){
        $this->middleware(['role:dokter']);
    }

    public function viewklpcm(){
        $data = User::get();
        return view('dokter.viewklpcm', compact('data'));
    }
}
