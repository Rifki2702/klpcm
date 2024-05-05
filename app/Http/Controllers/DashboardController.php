<?php

namespace App\Http\Controllers;

use App\Charts\KuantitatifChart;
use App\Charts\KualitatifChart;
use App\Models\Analisis;
use App\Models\Formulir;
use App\Models\Kelengkapan;
use App\Models\User;
use App\Models\Pasien;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(KuantitatifChart $KuantitatifChart, KualitatifChart $KualitatifChart)
    {
        $jumlahTidakLengkap = Kelengkapan::where('kuantitatif', false)
            ->select('analisis_id', DB::raw('count(*) as total'))
            ->groupBy('analisis_id')
            ->get()
            ->pluck('total', 'analisis_id');

        $jumlahAnalisis = Analisis::count(); // Menambahkan jumlah analisis

        // Mengambil data untuk chart kuantitatif
        $data['KuantitatifChart'] = $KuantitatifChart->build();
        $data['KualitatifChart'] = $KualitatifChart->build();
        $data['jumlahUser'] = User::count();
        $data['jumlahPasien'] = Pasien::count();
        $data['jumlahFormulir'] = Formulir::count();
        $data['jumlahTidakLengkap'] = $jumlahTidakLengkap;
        $data['jumlahAnalisis'] = $jumlahAnalisis; // Menyimpan jumlah analisis

        $data['ketepatanAnalisis'] = DB::table('ketepatan')
            ->where('ketepatan', true)
            ->count();
        $analisis = Analisis::whereHas('user.roles', function ($query) {
            $query->where('name', 'dokter');
        })->get();

        $data['dataAnalisis'] = $analisis->map(function ($item) {
            return [
                'analisis' => $item
            ];
        });

        return view('manajemen.dashboard', $data);
    }

    public function editprofile(Request $request)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            abort(404);
        }
        return view('manajemen.editprofile', compact('user'));
    }

    public function updateuser(Request $request)
    {
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $id,
            'gender'    => 'required|in:Laki-laki,Perempuan',
            'password'  => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.dashboard')->withErrors('User not found.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->gender = $request->gender;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
    }
}
