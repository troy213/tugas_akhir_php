<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tbl_biodata;
class Biodata extends Controller
{
  public function getData(){
    $data = DB::table('tbl_biodata')->get();
    if(count($data) > 0){
      $res['message'] = "Success!";
      $res['value'] = $data;
      return response($res);
    }else{
      $res['message'] = "Empty!";
      return response($res);
    }
  }

  public function simpan(Request $request){
    $this->validate($request, [
      'file' => 'required|max:2048'
    ]);
    $file = $request->file('file');
    $nama_file = time()."_".$file->getClientOriginalName();
    $tujuan_upload = 'data_file';
    if($file->move($tujuan_upload,$nama_file)){
      $data = tbl_biodata::create([
        'nama' => $request->nama,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'hobi' => $request->hobi,
        'foto' => $nama_file
      ]);
      $res['message'] = "Success!";
      $res['values'] = $data;
      return response($res);
    }
  }

  public function ubah(Request $request){
    if(!empty($request->file)){
      $this->validate($request, [
        'file'=> 'required|max:2048'
      ]);

      $file = $request->file('file');
      $nama_file = time()."_".$file->getClientOriginalName();

      $tujuan_upload = 'data_file';
      $file->move($tujuan_upload,$nama_file);
      $data = DB::table('tbl_biodata')->where('id',$request->id)->get();
      foreach ($data as $biodata){
        @unlink(public_path('data_file/'.$biodata->foto));
        $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
          'nama' => $request->nama,
          'no_hp' => $request->no_hp,
          'alamat' => $request->alamat,
          'hobi' => $request->hobi,
          'foto' => $nama_file
          ]);
        $res['message'] = "Success!";
        $res['values'] = $ket;
        return response($res);
      }
    }else{
      $data = DB::table('tbl_biodata')->where('id',$request->id)->get();
      foreach ($data as $biodata){
        @unlink(public_path('data_file/'.$biodata->foto));
        $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
          'nama' => $request->nama,
          'no_hp' => $request->no_hp,
          'alamat' => $request->alamat,
          'hobi' => $request->hobi,
          'foto' => null
          ]);
      $res['message'] = "Success!";
      $res['values'] = $ket;
      return response($res);
      }
    }
  }

  public function hapus($id){
    $data = DB::table('tbl_biodata')->where('id',$id)->get();
    foreach ($data as $biodata) {
      if (file_exists(public_path('data_file/'.$biodata->foto))){
        @unlink(public_path('data_file/'.$biodata->foto));
        DB::table('tbl_biodata')->where('id',$id)->delete();
        $res['message'] = "Success!";
        return response($res);
      }else{
        $res['message'] = "Empty!";
        return response($res);
      }
    }
  }

  public function baca($id){
    $data = DB::table('tbl_biodata')->where('id',$id)->get();
    if(count($data) > 0){
      $res['message'] = "Success!";
      $res['value'] = $data;
      return response($res);
    }else{
      $res['message'] = "Empty!";
      return response($res);
    }
  }
}
