<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RMController extends Controller
{
    public function __construct(){
        $this->middleware(['role:admin|rm']);    
    }    

    public function pasienmanagement(){
        $data = Pasien::get();
        return view('rm.pasien.pasienmanagement', compact('data'));
    }

    public function createpasien(){
        return view('rm.pasien.createpasien');
    }

    public function insertpasien(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'rm'        => 'required|unique:pasiens',
            'tgl_lahir' => 'required',
            'gender'    => 'required',
        ]);
    
        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $data['name']       = $request->name;
        $data['rm']         = $request->rm;
        $data['tgl_lahir']  = $request->tgl_lahir;
        $data['gender']     = $request->gender;
    
        Pasien::create($data);
    
        return redirect()->route('admin.pasienmanagement');
        return redirect()->route('admin.pasienmanagement')->with('success', 'Data berhasil ditambah.');
    }

    public function editpasien(Request $request,$id){
        $data = Pasien::find($id);

        return view('rm.pasien.editpasien',compact('data'));
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
    
    public function deletepasien(Request $request,$id){
        $data = Pasien::find($id);

        if($data){
            $data->delete();
        }
    
        return redirect()->route('admin.pasienmanagement')->with('danger', 'Data berhasil dihapus.');
    }

    public function analisismanagement(){
        $data = Pasien::get();
        return view('rm.analisis.analisismanagement', compact('data'));
    }


    public function analisislama($id){
        $pasien = Pasien::findOrFail($id);
        $rm_pasien = $pasien->rm;
        
        return view('rm.analisis.analisislama', compact('rm_pasien'));
    }

    public function analisisbaru($id){
        $pasien = Pasien::findOrFail($id);
        $rm_pasien = $pasien->rm;
        
        return view('rm.analisis.analisisbaru', compact('rm_pasien'));
    }

}
