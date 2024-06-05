<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Dokter;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index()
    {
        $users = User::all(); // Ambil semua data pengguna
        return view('user.index', compact('users'));
    }

    public function usermanagement()
    {
        $roles = Role::all();
        $data = User::get();
        return view('admin.users.usermanagement', compact('data', 'roles'));
    }

    public function createuser()
    {
        $roles = Role::all(); // Mendapatkan semua roles dari tabel roles

        return view('admin.users.createuser', compact('roles')); // Mengirimkan roles ke dalam view
    }

    public function insertuser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email',
            'gender'   => 'required',
            'password' => 'required',
            'role_id'  => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data['name']       = $request->name;
        $data['email']      = $request->email;
        $data['gender']     = $request->gender;
        $data['password']   = Hash::make($request->password);

        // Cek apakah ada file foto yang diupload
        if ($request->hasFile('foto')) {
            $foto     = $request->file('foto');
            $filename = date('Y-m-d') . $foto->getClientOriginalName();
            $path     = '/foto-user/' . $filename;

            Storage::disk('public')->put($path, file_get_contents($foto));

            $data['image'] = $filename;
        }

        // Simpan data pengguna baru
        $user = User::create($data);

        // Simpan relasi antara pengguna dan peran
        $user->roles()->attach($request->role_id);

        return redirect()->route('admin.usermanagement')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($userId)
    {
        $user = User::find($userId);
        $roleName = $user->role->name;

        return view('user.show', compact('user', 'roleName'));
    }

    public function edituser($id)
    {
        $data = User::findOrFail($id);
        $roles = Role::all(); // Ambil semua data peran (roles)
        return view('admin.users.edituser', compact('data', 'roles'));
    }

    public function updateuser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'role_id'   => 'required',
            'gender'    => 'required',
            'password'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data['name']       = $request->name;
        $data['email']      = $request->email;
        $data['role_id']    = $request->role_id;
        $data['gender']     = $request->gender;

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data pengguna
        $user = User::findOrFail($id);
        $user->update($data);

        return redirect()->route('admin.usermanagement')->with('warning', 'Data berhasil diupdate.');
    }

    public function deleteuser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.usermanagement')->with('danger', 'Data tidak ditemukan.');
        }

        // Hapus data yang terkait dari tabel analisis
        $analisis = Analisis::where('user_id', $id)->get();
        foreach ($analisis as $a) {
            $a->delete();
        }

        // Hapus data dari tabel users
        $user->delete();

        return redirect()->route('admin.usermanagement')->with('danger', 'Data berhasil dihapus.');
    }

    public function doktermanagement()
    {
        $dokter = Dokter::all();
        return view('admin.dokter.doktermanagement', compact('dokter'));
    }
    public function insertdokter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokter' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dokter = new Dokter();
        $dokter->nama_dokter = $request->nama_dokter;
        $dokter->save();

        return redirect()->route('admin.doktermanagement')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function editdokter(Request $request, $id)
    {
        // Debugging line to check the ID
        if (is_null($id)) {
            return redirect()->back()->with('error', 'ID Dokter tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_dokter' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ensure that $dokter is a single model instance
        $dokter = Dokter::findOrFail($id); // This ensures $dokter is a model, not a collection
        $dokter->nama_dokter = $request->nama_dokter;
        $dokter->save();

        return redirect()->route('admin.doktermanagement')->with('warning', 'Data dokter berhasil diupdate.');
    }

    public function deletedokter($id)
    {
        $dokter = Dokter::find($id);

        if (!$dokter) {
            return redirect()->route('admin.doktermanagement')->with('danger', 'Dokter tidak ditemukan.');
        }

        $dokter->delete();

        return redirect()->route('admin.doktermanagement')->with('danger', 'Dokter berhasil dihapus.');
    }
}
