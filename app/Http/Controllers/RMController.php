<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Formulir;
use App\Models\IsiForm;
use App\Models\Kelengkapan;
use App\Models\Ketepatan;
use App\Models\Kualitatif;
use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\User;
use App\Models\Dokter;
use App\Notifications\KelengkapanNotification;
use Exception;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RMController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|rm'])->except(['pasienmanagement', 'createpasien', 'insertpasien', 'editpasien', 'updatepasien', 'deletepasien', 'analisismanagement', 'analisislama', 'analisisbaru', 'insertawal', 'insertForm', 'hasil']);
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

        $data = [
            'name'       => $request->name,
            'rm'         => $request->rm,
            'tgl_lahir'  => $request->tgl_lahir,
            'gender'     => $request->gender,
        ];

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

        $data = [
            'name'      => $request->name,
            'rm'        => $request->rm,
            'tgl_lahir' => $request->tgl_lahir,
            'gender'    => $request->gender,
        ];

        Pasien::whereId($id)->update($data);

        return redirect()->route('admin.pasienmanagement')->with('warning', 'Data berhasil diupdate.');
    }

    public function deletepasien(Request $request, $id)
    {
        // Nonaktifkan sementara foreign key constraint
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Hapus semua data analisis yang terkait dengan pasien yang akan dihapus
        Analisis::where('pasien_id', $id)->delete();

        // Hapus semua data kelengkapan yang terkait dengan analisis yang terhubung dengan pasien yang akan dihapus
        Kelengkapan::whereIn('analisis_id', function ($query) use ($id) {
            $query->select('id')
                ->from('analisis')
                ->where('pasien_id', $id);
        })->delete();

        // Hapus data pasien
        $data = Pasien::find($id);

        if ($data) {
            $data->delete();
        }

        // Aktifkan kembali foreign key constraint
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

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
        $usersRuangan = User::whereHas('roles', function ($query) {
            $query->where('name', 'ruangan');
        })->get();
        $dokter = Dokter::all();

        $pasien = Pasien::findOrFail($id);

        // Ambil semua data analisis yang terkait dengan pasien dengan eager loading
        $analisis = Analisis::where('pasien_id', $id)
            ->with(['kelengkapans', 'ketepatans']) // Tambahkan eager loading untuk 'ketepatans'
            ->get();
        // Buat array untuk menyimpan hasil jumlah kuantitatif, jumlah kualitatif, dan persentase
        $hasilJumlahKuantitatif = [];
        $hasilJumlahKualitatif = [];

        // Iterasi semua data analisis
        foreach ($analisis as $item) {
            // Pastikan kelengkapan dan ketepatan tidak null sebelum melakukan operasi
            if ($item->kelengkapans && $item->ketepatans) {
                // Hitung jumlah kuantitatif yang memiliki nilai boolean 1
                $jumlahKuantitatif = $item->kelengkapans->where('kuantitatif', true)->count();
                // Hitung jumlah kualitatif yang memiliki nilai boolean 1 pada tabel ketepatan
                $jumlahKualitatif = $item->ketepatans->where('ketepatan', true)->count();

                // Hitung persentase kelengkapan kuantitatif
                $persentaseKuantitatif = count($item->kelengkapans) > 0 ? round(($jumlahKuantitatif / count($item->kelengkapans)) * 100, 2) : 0;
                // Hitung persentase ketepatan kualitatif
                $persentaseKualitatif = count($item->ketepatans) > 0 ? round(($jumlahKualitatif / count($item->ketepatans)) * 100, 2) : 0;

                // Simpan hasil jumlah kuantitatif dan persentase ke dalam array
                $hasilJumlahKuantitatif[$item->id] = [
                    'jumlah' => $jumlahKuantitatif,
                    'persentase' => $persentaseKuantitatif
                ];
                // Simpan hasil jumlah kualitatif dan persentase ke dalam array
                $hasilJumlahKualitatif[$item->id] = [
                    'jumlah' => $jumlahKualitatif,
                    'persentase' => $persentaseKualitatif
                ];
            } else {
                // Jika kelengkapan atau ketepatan adalah null, set nilai default
                $hasilJumlahKuantitatif[$item->id] = [
                    'jumlah' => 0,
                    'persentase' => 0
                ];
                $hasilJumlahKualitatif[$item->id] = [
                    'jumlah' => 0,
                    'persentase' => 0 // Set persentase ketepatan kualitatif ke 100 jika tidak ada data
                ];
            }
        }

        // Kirim data ke view
        return view('rm.analisis.analisislama', compact('analisis', 'hasilJumlahKuantitatif', 'hasilJumlahKualitatif', 'pasien', 'usersRuangan', 'dokter'));
    }

    public function analisisbaru($analisis_id)
    {
        $analisis = Analisis::findOrFail($analisis_id);
        $rm_pasien = $analisis->pasien->rm;
        $dokter = Dokter::all();

        $usersRuangan = User::whereHas('roles', function ($query) {
            $query->where('name', 'ruangan');
        })->get();

        $formulirs = Formulir::whereDoesntHave('kelengkapans', function ($query) use ($analisis_id) {
            $query->where('analisis_id', $analisis_id);
        })->get();

        if ($formulirs->isEmpty()) {
            return view('rm.analisis.hasil', ['analisis_id' => $analisis_id])->with('warning', 'Tidak ada formulir yang tersedia.');
        }

        return view('rm.analisis.analisisbaru', compact('rm_pasien', 'usersRuangan', 'formulirs', 'analisis', 'dokter'));
    }

    public function insertawal(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'ruangan' => 'required|exists:users,id',
            'dokter_id' => 'required|exists:dokters,id', // Perbaiki nama field validasi untuk dokter_id
        ]);

        // Simpan data ke dalam tabel analisis
        $analisis = new Analisis;
        $analisis->user_id = $request->input('ruangan'); // Isi user_id dengan ID ruangan yang dipilih
        $analisis->pasien_id = $request->input('pasien_id');
        $analisis->dokter_id = $request->input('dokter_id'); // Gunakan input dokter_id yang telah divalidasi
        $analisis->tglberkas = $request->input('tanggal');
        $analisis->tglcek = now();

        // Simpan data analisis
        $analisis->save();

        // Redirect ke halaman analisis selanjutnya dengan memeriksa apakah formulir tersedia untuk menghindari pengalihan berlebihan
        return redirect()->route('admin.analisisbaru', ['analisis_id' => $analisis->id]);
    }

    public function insertForm(Request $request)
    {
        // Validasi request
        DB::beginTransaction();
        $validated = $request->validate([
            'analisis_id' => 'required|exists:analisis,id',
            'kuantitatif' => 'required|array',
            'kuantitatif.*' => 'boolean',
        ]);
        try {
            $dataKelengkapans = [];
            $containsFalse = false; // Flag untuk menandakan apakah terdapat nilai boolean false

            // Iterasi semua inputan kuantitatif
            foreach ($request->kuantitatif as $isiFormId => $statusKuantitatif) {
                // Cek apakah IsiForm tersedia
                $isiForm = IsiForm::find($isiFormId);
                if (!$isiForm) {
                    return redirect()->back()->with('error', 'IsiForm tidak ditemukan');
                }

                // Persiapkan data untuk disimpan
                $dataKelengkapans[] = [
                    'analisis_id' => $request->analisis_id,
                    'formulir_id' => $isiForm->formulir_id,
                    'isi_form_id' => $isiFormId,
                    'kuantitatif' => $statusKuantitatif,
                ];

                // Periksa jika terdapat nilai boolean false
                if (!$statusKuantitatif) {
                    $containsFalse = true;
                }
            }

            // Simpan semua data kuantitatif sekaligus ke dalam tabel Kelengkapan
            // $id = DB::table('kelengkapan')->insertGetId([$dataKelengkapans]);
            Kelengkapan::insert($dataKelengkapans);

            // Kirim notifikasi
            $analisis = Analisis::find($request->analisis_id);
            $user = User::find($analisis->user_id);

            $analisisData = Analisis::where('pasien_id', $analisis->pasien_id)
                ->with(['kelengkapans', 'ketepatans']) // Tambahkan eager loading untuk 'ketepatans'
                ->get();
            // Persentase
            $hasilJumlahKuantitatif = [];
            foreach ($analisisData as $item) {
                // Pastikan kelengkapan dan ketepatan tidak null sebelum melakukan operasi
                if ($item->kelengkapans && $item->ketepatans) {
                    // Hitung jumlah kuantitatif yang memiliki nilai boolean 1
                    $jumlahKuantitatif = $item->kelengkapans->where('kuantitatif', true)->count();
                    // Hitung jumlah kualitatif yang memiliki nilai boolean 1 pada tabel ketepatan
                    $jumlahKualitatif = $item->ketepatans->where('ketepatan', true)->count();

                    // Hitung persentase kelengkapan kuantitatif
                    $persentaseKuantitatif = count($item->kelengkapans) > 0 ? round(($jumlahKuantitatif / count($item->kelengkapans)) * 100, 2) : 0;
                    // Hitung persentase ketepatan kualitatif
                    $persentaseKualitatif = count($item->ketepatans) > 0 ? round(($jumlahKualitatif / count($item->ketepatans)) * 100, 2) : 0;

                    // Simpan hasil jumlah kuantitatif dan persentase ke dalam array
                    $hasilJumlahKuantitatif[$item->id] = [
                        'jumlah' => $jumlahKuantitatif,
                        'persentase' => $persentaseKuantitatif
                    ];
                } else {
                    // Jika kelengkapan atau ketepatan adalah null, set nilai default
                    $hasilJumlahKuantitatif[$item->id] = [
                        'jumlah' => 0,
                        'persentase' => 0
                    ];
                }
            }
            // Kirim notifikasi hanya jika terdapat nilai boolean false
            $id_test = Analisis::latest()->first()->id;
            if ($containsFalse) {
                $noRM = $analisis->pasien->rm;
                $tanggalLengkapi = now()->addDays(2)->format('d-m-Y');
                $data = [
                    'message' => 'Terdapat Data belum lengkap dengan nomor RM ' . $noRM . '. Lengkapi sebelum tanggal ' . $tanggalLengkapi,
                    'link' => route('admin.viewklpcm'),
                    'is_complete' => false,
                    'analisis' => number_format($hasilJumlahKuantitatif[$id_test]['persentase'], 2) . '%',
                ];
                Notification::send($user, new KelengkapanNotification($data));
            } else {
                $data = [
                    'message' => 'Data lengkap',
                    'link' => route('admin.viewklpcm'),
                    'is_complete' => true,
                    'analisis' => number_format($hasilJumlahKuantitatif[$id_test]['persentase'], 2) . '%',
                ];
                Notification::send($user, new KelengkapanNotification($data));
            }
            DB::commit();
            // Redirect dengan pesan sukses dan parameter analisis_id
            return redirect()->route('admin.analisiskualitatif', ['analisis_id' => $request->analisis_id]);
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function editkuantitatif($analisis_id)
    {
        $analisis = Analisis::findOrFail($analisis_id);
        $rm_pasien = $analisis->pasien->rm;

        $usersRuangan = User::whereHas('roles', function ($query) {
            $query->where('name', 'ruangan');
        })->get();

        $formulirs = Formulir::with(['isiForms' => function ($query) use ($analisis_id) {
            $query->with(['kelengkapan' => function ($query) use ($analisis_id) {
                $query->where('analisis_id', $analisis_id)->select('isi_form_id', 'kuantitatif');
            }]);
        }])->get();

        return view('rm.analisis.edit.editkuantitatif', compact('rm_pasien', 'usersRuangan', 'formulirs', 'analisis'));
    }

    public function updateform(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'analisis_id' => 'required|exists:analisis,id',
            'kuantitatif' => 'required|array',
            'kuantitatif.*' => 'boolean',
        ]);

        // Iterasi semua inputan kuantitatif
        foreach ($request->kuantitatif as $isiFormId => $statusKuantitatif) {
            // Cek apakah data Kelengkapan sudah ada untuk IsiForm yang bersangkutan
            $kelengkapan = Kelengkapan::where('analisis_id', $request->analisis_id)
                ->where('isi_form_id', $isiFormId)
                ->first();

            if ($kelengkapan) {
                $kelengkapan->kuantitatif = (int)$statusKuantitatif; // Pastikan nilai boolean dikonversi ke integer
            } else {
                // Jika belum ada, buat instance baru
                $kelengkapan = new Kelengkapan([
                    'analisis_id' => $request->analisis_id,
                    'formulir_id' => IsiForm::find($isiFormId)->formulir_id,
                    'isi_form_id' => $isiFormId,
                    'kuantitatif' => (int)$statusKuantitatif, // Pastikan nilai boolean dikonversi ke integer
                ]);
            }
            // Simpan perubahan atau data baru
            $kelengkapan->save();
        }

        // Redirect ke halaman edit kualitatif setelah update berhasil
        if ($kelengkapan->save()) {
            return redirect()->route('admin.editkualitatif', ['analisis_id' => $request->analisis_id]);
        } else {
            return back()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function analisiskualitatif($analisisId)
    {
        $analisis = Analisis::findOrFail($analisisId);
        $usersRuangan = User::whereHas('roles', function ($query) {
            $query->where('name', 'ruangan');
        })->get();
        $rm_pasien = $analisis->pasien->rm;
        $kualitatifs = Kualitatif::all(); // Mengambil semua data dari tabel kualitatif
        $ketepatan = Ketepatan::all();

        return view('rm.analisis.analisiskualitatif', compact('analisis', 'rm_pasien', 'usersRuangan', 'kualitatifs', 'ketepatan'));
    }

    public function editkualitatif($analisis_id)
    {
        $analisis = Analisis::findOrFail($analisis_id);
        $rm_pasien = $analisis->pasien->rm;

        $usersRuangan = User::whereHas('roles', function ($query) {
            $query->where('name', 'ruangan');
        })->get();

        // Ambil data Ketepatan yang terkait dengan analisis_id
        $ketepatans = Ketepatan::where('analisis_id', $analisis_id)->with('kualitatif')->get();

        return view('rm.analisis.edit.editkualitatif', compact('rm_pasien', 'usersRuangan', 'ketepatans', 'analisis'));
    }

    public function insertkualitatif(Request $request)
    {
        // Validasi data formulir
        $validatedData = $request->validate([
            'analisis_id' => 'required',
            'kualitatif.*' => 'required|in:0,1',
        ]);

        foreach ($request->kualitatif as $kualitatifId => $value) {
            $kualitatif = Kualitatif::findOrFail($kualitatifId);
            $kualitatif->ketepatans()->updateOrCreate(
                ['analisis_id' => $request->analisis_id],
                ['ketepatan' => $value ? 1 : 0]
            );
        }

        return redirect()->route('admin.hasil', ['analisis_id' => $request->analisis_id])->with('success', 'Data berhasil disimpan');
    }

    public function updatekualitatif(Request $request)
    {
        $id = $request->analisis_id;
        $analisis = Analisis::findOrFail($id);

        foreach ($request->kualitatif as $key => $value) {
            $analisis->ketepatans()->where('id', $key)->update(['ketepatan' => $value]);
        }

        return redirect()->route('admin.hasil', ['analisis_id' => $id])->with('success', 'Data berhasil disimpan');
    }

    public function hasil($analisisId)
    {
        // Ambil data analisis dari database termasuk data dokter, pasien, dan semua kelengkapan
        $analisis = Analisis::with(['user', 'pasien', 'kelengkapans', 'ketepatans', 'dokter'])->find($analisisId);

        // Pastikan data analisis ditemukan
        if (!$analisis) {
            abort(404, 'Analisis tidak ditemukan.');
        }

        // Hitung jumlah kuantitatif yang memiliki nilai boolean 1
        $jumlahKuantitatif = $analisis->kelengkapans->where('kuantitatif', true)->count();

        // Hitung persentase kelengkapan kuantitatif
        $persentaseKuantitatif = count($analisis->kelengkapans) > 0 ? round(($jumlahKuantitatif / count($analisis->kelengkapans)) * 100, 2) : 0;

        // Hitung jumlah kualitatif yang memiliki nilai boolean 1
        $jumlahKualitatif = $analisis->ketepatans->where('ketepatan', true)->count();

        // Hitung persentase ketepatan kualitatif
        $persentaseKualitatif = count($analisis->ketepatans) > 0 ? round(($jumlahKualitatif / count($analisis->ketepatans)) * 100, 2) : 0;

        // Kirim data ke view
        return view('rm.analisis.hasil', compact('analisis', 'persentaseKuantitatif', 'persentaseKualitatif'));
    }

    public function pdf($analisisId)
    {
        // Ambil data analisis dari database termasuk data dokter, pasien, dan semua kelengkapan
        $analisis = Analisis::with(['user', 'pasien', 'kelengkapans', 'ketepatans', 'dokter'])->find($analisisId);

        // Pastikan data analisis ditemukan
        if (!$analisis) {
            abort(404, 'Analisis tidak ditemukan.');
        }

        // Hitung jumlah kuantitatif yang memiliki nilai boolean 1
        $jumlahKuantitatif = $analisis->kelengkapans->where('kuantitatif', true)->count();

        // Hitung persentase kelengkapan kuantitatif
        $persentaseKuantitatif = count($analisis->kelengkapans) > 0 ? round(($jumlahKuantitatif / count($analisis->kelengkapans)) * 100, 2) : 0;

        // Hitung jumlah kualitatif yang memiliki nilai boolean 1
        $jumlahKualitatif = $analisis->ketepatans->where('ketepatan', true)->count();

        // Hitung persentase ketepatan kualitatif
        $persentaseKualitatif = count($analisis->ketepatans) > 0 ? round(($jumlahKualitatif / count($analisis->ketepatans)) * 100, 2) : 0;

        // Kirim data ke view
        return view('rm.analisis.pdf', compact('analisis', 'persentaseKuantitatif', 'persentaseKualitatif'));
    }
}
