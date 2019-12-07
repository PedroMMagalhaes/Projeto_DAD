<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class FileController extends Controller
{
    public function storePhoto(Request $request){
        if($request->hasFile('file')){
            $file = \Storage::putFile('public/fotos', $request->file);
            $file = basename($file);
            return $file;
        }else{
            return "No file input";
        }
    }
}