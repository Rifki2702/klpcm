<?php

namespace App\Http\Controllers;

use App\Charts\KuantitatifChart;
use App\Charts\KualitatifChart;
use App\Models\Analisis;
use App\Models\Formulir;
use App\Models\Kelengkapan;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $id = Auth::id(); // Menggunakan ID dari user yang terautentikasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'gender' => 'required|in:Laki-laki,Perempuan',
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('photos', 'public');
            $data['photo'] = $imagePath;
        }

        $user = User::findOrFail($id);
        $user->update($data);

        return redirect()->route('dashboard')->with('success', 'Data berhasil diperbarui');
    }
}
