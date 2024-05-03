<?php

namespace App\Http\Controllers;

use App\Charts\KuantitatifChart;
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

class DashboardController extends Controller
{
    public function dashboard(KuantitatifChart $KuantitatifChart)
    {
        $jumlahTidakLengkap = Kelengkapan::where('kuantitatif', false)
            ->select('analisis_id', DB::raw('count(*) as total'))
            ->groupBy('analisis_id')
            ->get()
            ->pluck('total', 'analisis_id');

        $jumlahAnalisis = Analisis::count(); // Menambahkan jumlah analisis

        $data['KuantitatifChart'] = $KuantitatifChart->build('daily');
        $data['jumlahUser'] = User::count();
        $data['jumlahPasien'] = Pasien::count();
        $data['jumlahFormulir'] = Formulir::count();
        $data['jumlahTidakLengkap'] = $jumlahTidakLengkap;
        $data['jumlahAnalisis'] = $jumlahAnalisis; // Menyimpan jumlah analisis

        // Menghitung jumlah keseluruhan kelengkapan kuantitatif yang harus ada
        $jumlahKuantitatifTotal = Kelengkapan::where('kuantitatif', true)->count();

        // Menghitung jumlah kelengkapan kuantitatif yang lengkap (nilai true)
        $jumlahKuantitatifLengkap = Kelengkapan::where('kuantitatif', true)
            ->where('kuantitatif', true)
            ->count();

        // Menghitung persentase kuantitatif keseluruhan
        $persentaseKuantitatifKeseluruhan = $jumlahKuantitatifTotal > 0 ? round(($jumlahKuantitatifLengkap / $jumlahKuantitatifTotal) * 100, 2) : 0;

        $data['persentaseKuantitatifKeseluruhan'] = $persentaseKuantitatifKeseluruhan;

        $analisis = Analisis::whereHas('user.roles', function ($query) {
            $query->where('name', 'dokter');
        })
            ->withCount(['kelengkapans as kelengkapan_count' => function ($query) {
                $query->where('kuantitatif', true);
            }])
            ->withCount(['kelengkapans as kuantitatif_count' => function ($query) {
                $query->where('kuantitatif', true);
            }])
            ->withCount(['ketepatans as kualitatif_count' => function ($query) {
                $query->where('ketepatan', false);
            }])
            ->get();

        $data['dataAnalisis'] = $analisis->map(function ($item) {
            $persentaseKuantitatif = $item->kuantitatif_count > 0 ? round(($item->kelengkapan_count / $item->kuantitatif_count) * 100, 2) : 0;

            return [
                'analisis' => $item,
                'persentase' => [
                    'kuantitatif' => $persentaseKuantitatif,
                ]
            ];
        });

        return view('manajemen.dashboard', $data);
    }

    public function editptofile(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }
        return view('admin.editprofile', compact('data'));
    }

    public function updateuser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'level'     => 'required',
            'gender'    => 'required',
            'password'  => 'nullable',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data['name']       = $request->name;
        $data['email']      = $request->email;
        $data['level']      = $request->level;
        $data['gender']     = $request->gender;

        if ($request->password) {
            $data['password']   = Hash::make($request->password);
        }

        User::whereId($id)->update($data);

        return redirect()->route('admin.usermanagement');
    }
}
