<?php


namespace App\Http\Utiles;


use Illuminate\Support\Facades\File;

class UploadFile
{
    public static function upload($request,$folder){
            $file_Extension = $request->getClientOriginalName();
            $request->move($folder,$file_Extension);
            return $file_Extension;
    }
    public static function delete($name,$folder){
            if (File::exists( $folder.'/'.$name)) {
                File::delete($folder.'/'.$name);
            }
    }
}
