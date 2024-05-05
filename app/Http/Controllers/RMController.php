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
        $usersDokter = User::whereHas('roles', function ($query) {
            $query->where('name', 'dokter');
        })->get();

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
        return view('rm.analisis.analisislama', compact('analisis', 'hasilJumlahKuantitatif', 'hasilJumlahKualitatif', 'pasien', 'usersDokter'));
    }

    public function analisisbaru($analisis_id)
    {
        $analisis = Analisis::findOrFail($analisis_id);
        $rm_pasien = $analisis->pasien->rm;

        $usersDokter = User::whereHas('roles', function ($query) {
            $query->where('name', 'dokter');
        })->get();

        $formulirs = Formulir::whereDoesntHave('kelengkapans', function ($query) use ($analisis_id) {
            $query->where('analisis_id', $analisis_id);
        })->get();

        // Untuk menghindari terlalu banyak pengalihan, kita akan mengembalikan view langsung jika formulir kosong tanpa melakukan redirect
        if ($formulirs->isEmpty()) {
            return view('rm.analisis.hasil', ['analisis_id' => $analisis_id])->with('warning', 'Tidak ada formulir yang tersedia.');
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
        $analisis->user_id = $request->input('dokter'); // Isi user_id dengan ID dokter yang dipilih
        $analisis->pasien_id = $request->input('pasien_id');
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
                    'analisis' => number_format($hasilJumlahKuantitatif[$id_test]['persentase'], 2).'%',
                ];
                Notification::send($user, new KelengkapanNotification($data));
            } else {
                $data = [
                    'message' => 'Data lengkap',
                    'link' => route('admin.viewklpcm'),
                    'is_complete' => true,
                    'analisis' => number_format($hasilJumlahKuantitatif[$id_test]['persentase'], 2).'%',
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

        $usersDokter = User::whereHas('roles', function ($query) {
            $query->where('name', 'dokter');
        })->get();

        $formulirs = Formulir::with(['kelengkapans' => function ($query) use ($analisis_id) {
            $query->where('analisis_id', $analisis_id)->select('formulir_id', 'kuantitatif');
        }])->whereHas('kelengkapans', function ($query) use ($analisis_id) {
            $query->where('analisis_id', $analisis_id);
        })->get();

        return view('rm.analisis.edit.editkuantitatif', compact('rm_pasien', 'usersDokter', 'formulirs', 'analisis'));
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
        $rm_pasien = $analisis->pasien->rm;
        $usersDokter = User::where('role_id', 3)->get(); // Assuming 3 is the role_id for dokter
        $formulirs = Formulir::all();
        $kualitatifs = Kualitatif::all(); // Mengambil semua data dari tabel kualitatif
        $ketepatan = Ketepatan::all();

        return view('rm.analisis.analisiskualitatif', compact('analisis', 'rm_pasien', 'usersDokter', 'formulirs', 'kualitatifs', 'ketepatan'));
    }

    public function editkualitatif($analisisId)
    {
        $analisis = Analisis::findOrFail($analisisId);
        $rm_pasien = $analisis->pasien->rm;
        $usersDokter = User::where('role_id', 3)->get(); // Assuming 3 is the role_id for dokter
        $formulirs = Formulir::all();
        $kualitatifs = Kualitatif::all(); // Mengambil semua data dari tabel kualitatif
        $ketepatan = Ketepatan::all();

        return view('rm.analisis.edit.editkualitatif', compact('analisis', 'rm_pasien', 'usersDokter', 'formulirs', 'kualitatifs', 'ketepatan'));
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
        // Validasi data formulir
        $validatedData = $request->validate([
            'analisis_id' => 'required',
            'kualitatif.*' => 'required|in:0,1',
        ]);

        // Periksa apakah $request->kualitatif adalah array atau objek yang valid
        if (!is_array($request->kualitatif) && !is_object($request->kualitatif)) {
            // Tampilkan pesan error atau lakukan penanganan lainnya
            return redirect()->back()->with('error', 'Data kualitatif tidak valid');
        }

        // Lakukan iterasi jika $request->kualitatif adalah array atau objek yang valid
        foreach ($request->kualitatif as $kualitatifId => $value) {
            $kualitatif = Kualitatif::findOrFail($kualitatifId);
            $kualitatif->ketepatans()->updateOrCreate(
                ['analisis_id' => $request->analisis_id],
                ['ketepatan' => $value ? 1 : 0]
            );
        }

        return redirect()->route('admin.analisislama', ['id' => $request->analisis_id])->with('success', 'Data berhasil disimpan');
    }

    public function hasil($analisisId)
    {
        // Ambil data analisis dari database termasuk data dokter, pasien, dan kelengkapan yang tidak lengkap (kuantitatif = 0)
        $analisis = Analisis::with(['user', 'pasien', 'kelengkapans' => function ($query) {
            $query->where('kuantitatif', 0)->with('formulir', 'isiForm');
        }])->find($analisisId);

        // Pastikan data analisis ditemukan
        if (!$analisis) {
            abort(404, 'Analisis tidak ditemukan.');
        }

        // Kirim data ke view
        return view('rm.analisis.hasil', compact('analisis'));
    }

    public function pdf($analisisId)
    {
        // Ambil data analisis dari database termasuk data dokter, pasien, dan kelengkapan yang tidak lengkap (kuantitatif = 0)
        $analisis = Analisis::with(['user', 'pasien', 'kelengkapans' => function ($query) {
            $query->where('kuantitatif', 0)->with('formulir', 'isiForm');
        }])->find($analisisId);

        // Pastikan data analisis ditemukan
        if (!$analisis) {
            abort(404, 'Analisis tidak ditemukan.');
        }

        // Buat objek MPDF
        $mpdf = new \Mpdf\Mpdf();

        // Tambahkan konten PDF dengan menggunakan data analisis
        $html = view('rm.analisis.pdf', compact('analisis'))->render();
        $mpdf->WriteHTML($html);

        // Buat nama file sesuai dengan format "analisis_rm_tglberkas.pdf"
        $filename = 'analisis_' . $analisis->pasien->rm . '_' . \Carbon\Carbon::parse($analisis->tglberkas)->format('dmY') . '.pdf';

        // Output sebagai file PDF dan langsung didownload dengan nama file yang sesuai
        $mpdf->Output($filename, 'D');
    }
}
