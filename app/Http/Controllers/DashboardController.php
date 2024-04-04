<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function dashboard(){
        $users = User::all();
        $jumlahUser = User::count();
        $jumlahPasien = Pasien::count();
        $jumlahFormulir = Formulir::count();
        return view('manajemen.dashboard', compact('jumlahUser', 'jumlahPasien', 'jumlahFormulir'));
    }

    public function editptofile(Request $request,$id){
        $user = User::find($id);

        if (!$user) {
            // Handle jika user tidak ditemukan
            abort(404);
        }
        return view('admin.editprofile',compact('data'));
    }

    public function updateuser(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'email'     => 'required|email',
            'level'     => 'required',
            'gender'    => 'required',
            'password'  => 'nullable',
        ]);

        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data['name']       = $request->name;
        $data['email']      = $request->email;
        $data['level']      = $request->level;
        $data['gender']     = $request->gender;

        if($request->password){
            $data['password']   = Hash::make($request->password);
        }

        User::whereId($id)->update($data);

        return redirect()->route('admin.usermanagement');
    }
}
