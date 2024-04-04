<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Formulir;
use App\Models\IsiForm;

class FormulirController extends Controller
{

    public function formulirmanagement(){
        $data = Formulir::get();
        return view('admin.formulir.formulirmanagement', compact('data'));
    }

    public function createformulir()
    {
        return view('admin.formulir.createformulir');
    }
    
    public function insertformulir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_formulir' => 'required|unique:formulir,nama_formulir',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $formulir = Formulir::create([
            'nama_formulir' => $request->nama_formulir,
        ]);
    
        return redirect()->route('admin.createisi', ['id' => $formulir->id]);
    }
    
    public function deleteformulir($id)
    {
        $formulir = Formulir::findOrFail($id);
        if (!$formulir) {
            return redirect()->back()->with('error', 'Formulir tidak ditemukan');
        }

        // Hapus juga isi formulir yang terkait
        $formulir->isiForms()->delete();
        $formulir->delete();

        return redirect()->back()->with('danger', 'Formulir berhasil dihapus');
    }


    public function someMethod()
    {
        $array = [1, 2, 3];
        $result = "Array: " . implode(", ", $array);
        return view('some_view', ['result' => $result]);
    }
    
    public function createisi($id)
    {
        $formulir = Formulir::findOrFail($id);
        $isiForms = $formulir->isiForms; // Mendapatkan semua isi formulir terkait
        return view('admin.formulir.createisi', compact('formulir', 'isiForms'));
    }
     
       
    public function insertisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formulir_id' => 'required',
            'isi' => 'required|array',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $isi = $request->isi;
        if (empty($isi)) {
            $isi = null;
        } else {
            $isi = implode("\n", $isi);
        }
    
        IsiForm::create([
            'formulir_id' => $request->formulir_id,
            'isi' => $isi,
        ]);
    
        return redirect()->back();
    }

    public function deleteisi($id)
    {
        $isiForm = IsiForm::find($id);
        if (!$isiForm) {
            return redirect()->back()->with('error', 'Isi Formulir tidak ditemukan');
        }

        $isiForm->delete();

        return redirect()->back()->with('success', 'Isi Formulir berhasil dihapus');
    }

}
