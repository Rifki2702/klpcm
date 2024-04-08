<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LaporanController extends Controller
{
    public function laporanmanagement()
    {
        $data = User::get();
        return view('admin.laporan.laporanmanagement', compact('data'));
    }
}
