<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Formulir;
use App\Models\IsiForm;
use App\Models\Kelengkapan;
use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RMController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|rm']);
    }

    public function pasienmanagement()
    {
        $data = Pasien::get();
        return view('rm.pasien.pasienmanagement', compact('data'));
    }

    public function createpasien()
    {
        return view('rm.pasien.createpasien');
    }

    public function insertpasien(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'rm'        => 'required|unique:pasiens',
            'tgl_lahir' => 'required',
            'gender'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data['name']       = $request->name;
        $data['rm']         = $request->rm;
        $data['tgl_lahir']  = $request->tgl_lahir;
        $data['gender']     = $request->gender;

        Pasien::create($data);

        return redirect()->route('admin.pasienmanagement')->with('success', 'Data berhasil ditambah.');
    }

    public function editpasien(Request $request, $id)
    {
        $data = Pasien::find($id);

        return view('rm.pasien.editpasien', compact('data'));
    }

    public function updatepasien(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'rm'        => 'required|unique:pasiens,rm,' . $id,
            'tgl_lahir' => 'required',
            'gender'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data['name']      = $request->name;
        $data['rm']        = $request->rm;
        $data['tgl_lahir'] = $request->tgl_lahir;
        $data['gender']    = $request->gender;

        Pasien::whereId($id)->update($data);

        return redirect()->route('admin.pasienmanagement')->with('warning', 'Data berhasil diupdate.');
    }

    public function deletepasien(Request $request, $id)
    {
        $data = Pasien::find($id);

        if ($data) {
            $data->delete();
        }

        return redirect()->route('admin.pasienmanagement')->with('danger', 'Data berhasil dihapus.');
    }

    public function analisismanagement()
    {
        $usersDokter = User::whereHas('roles', function ($query) {
            $query->where('name', 'dokter');
        })->get();
        $data = Pasien::get();
        return view('rm.analisis.analisismanagement', compact('data', 'usersDokter'));
    }

    public function analisislama($id)
    {
        $pasien = Pasien::findOrFail($id);
        $rm_pasien = $pasien->rm;

        return view('rm.analisis.analisislama', compact('rm_pasien'));
    }

    public function analisisbaru($analisis_id)
    {
        $analisis = Analisis::findOrFail($analisis_id);
        $rm_pasien = $analisis->pasien->rm;

        $usersDokter = User::whereHas('roles', function ($query) {
            $query->where('name', 'dokter');
        })->get();

        // Ambil semua formulir yang belum dianalisis untuk analisis tertentu
        $formulirs = Formulir::whereDoesntHave('kelengkapans', function ($query) use ($analisis_id) {
            $query->where('analisis_id', $analisis_id);
        })->get();

        if ($formulirs->isEmpty()) {
            return redirect()->route('admin.hasil');
        }

        return view('rm.analisis.analisisbaru', compact('rm_pasien', 'usersDokter', 'formulirs', 'analisis'));
    }

    public function insertawal(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'dokter' => 'required|exists:users,id',
        ]);

        // Simpan data ke dalam tabel analisis
        $analisis = new Analisis;
        $analisis->user_id = $request->dokter;
        $analisis->pasien_id = $request->pasien_id;
        $analisis->tglberkas = $request->tanggal;
        $analisis->tglcek = now();

        // Simpan data analisis
        $analisis->save();

        // Redirect ke halaman analisis selanjutnya
        return redirect()->route('admin.analisisbaru', ['analisis_id' => $analisis->id]);
    }

    public function insertForm(Request $request)
    {
        try {
            // Validasi request
            $validated = $request->validate([
                'analisis_id' => 'required|exists:analisis,id',
                'kuantitatif' => 'required|array',
                'kuantitatif.*' => 'boolean',
            ]);

            $dataKelengkapans = [];
            // Iterasi semua inputan kuantitatif
            foreach ($request->kuantitatif as $isiFormId => $statusKuantitatif) {
                // Cek apakah IsiForm tersedia
                $isiForm = IsiForm::find($isiFormId);
                if (!$isiForm) {
                    continue; // Jika tidak ditemukan, lanjutkan ke iterasi berikutnya
                }

                // Persiapkan data untuk disimpan
                $dataKelengkapans[] = [
                    'analisis_id' => $request->analisis_id,
                    'formulir_id' => $isiForm->formulir_id,
                    'isi_form_id' => $isiFormId,
                    'kuantitatif' => $statusKuantitatif,
                ];
            }

            // Simpan semua data kuantitatif sekaligus ke dalam tabel Kelengkapan
            Kelengkapan::insert($dataKelengkapans);

            // Redirect dengan pesan sukses
            return redirect()->route('admin.hasil')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            // Log error atau handle error
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function hasil()
    {
        $data = Pasien::get();
        return view('rm.analisis.hasil', compact('data'));
    }
}
