<?php


namespace App\Http\Utiles;


use Illuminate\Support\Facades\File;

class GETFile
{
    public static function get($file_id,$folder){
            $file = \App\Models\File::where('id',$file_id)->select('id','path')->first();
            return asset($folder.'/' . $file->path);
    }

}
