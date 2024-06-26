<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Dokter;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function usermanagement()
    {
        $roles = Role::all();
        $data = User::with('roles')->get();
        return view('admin.users.usermanagement', compact('data', 'roles'));
    }

    public function createuser()
    {
        $roles = Role::all();
        return view('admin.users.createuser', compact('roles'));
    }

    public function insertuser(Request $request)
    {
        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email|max:255',
            'gender'   => 'required|string|max:10',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan error
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ambil data yang valid dari request
        $data = $request->only(['name', 'email', 'gender']);
        $data['password'] = Hash::make($request->password); // Enkripsi password

        // Jika ada file foto yang diunggah, simpan file tersebut
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = Carbon::now()->translatedFormat('his') . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/user', $filename);
            $data['foto'] = 'user/' . $filename; // Tambahkan path foto ke data
        }

        // Buat user baru dengan data yang telah disiapkan
        $user = User::create($data);
        // Attach role ke user
        $user->roles()->attach($request->role_id);

        // Redirect ke halaman manajemen user dengan pesan sukses
        return redirect()->route('admin.usermanagement')->with('success', 'Data berhasil ditambahkan.');
    }

<<<<<<< HEAD
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $roleName = $user->roles->first()->name;

        return view('user.show', compact('user', 'roleName'));
    }

=======
>>>>>>> 36eecf08a2c7955fec53765269f7437cf8212087
    public function edituser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $id,
            'role_id'   => 'required|exists:roles,id',
            'gender'    => 'required|in:Laki-Laki,Perempuan',
            'password'  => 'nullable|string|min:8|confirmed',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->gender = $request->gender;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = Carbon::now()->translatedFormat('his') . Str::slug($request->name) . '.' . $file->extension();
            $file->storeAs('public/user', $filename);
            $user->foto = 'user/' . $filename;
        }

        $user->save();
        $user->roles()->sync([$request->role_id]);

        return redirect()->route('admin.usermanagement')->with('warning', 'Data berhasil diupdate.');
    }

    public function deleteuser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        Analisis::where('user_id', $id)->delete();
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

        Dokter::create($request->only('nama_dokter'));

        return redirect()->route('admin.doktermanagement')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function editdokter(Request $request, $id)
    {
        if (is_null($id)) {
            return redirect()->back()->with('error', 'ID Dokter tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_dokter' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dokter = Dokter::findOrFail($id);
        $dokter->nama_dokter = $request->nama_dokter;
        $dokter->save();

        return redirect()->route('admin.doktermanagement')->with('warning', 'Data dokter berhasil diupdate.');
    }

    public function deletedokter($id)
    {
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();

        return redirect()->route('admin.doktermanagement')->with('danger', 'Dokter berhasil dihapus.');
    }
}
