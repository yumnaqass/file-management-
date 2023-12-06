<?php 

namespace App\Http\Repositories;

use App\Models\Config;
use App\Models\File;
use App\Models\File_Group;
use App\Models\Group;


class AdminRepository
{
    private $file,$group_file,$group,$file_number;
    public function __construct(File $file,File_Group $group_file,Group $group,Config $config)
    {
        $this->file = $file;
        $this->group_file = $group_file;
        $this->group = $group;
        $this->file_number = $config;
    }

    public function getAllFileInSystem(){
        return $this->file->select('id','path')->paginate(30);
    }

    public function getAllGroupInSystem(){
        return $this->group->select('id','name')->paginate(30);
    }

    public function getAllFileInGroup($group_id){
        $a = $this->group_file->where('group_id',$group_id)->get('file_id');

        $array[0] = -5;
        for ($i=0 ; $i<sizeof($a) ;$i++)
        {
            $array[$i] = $a[$i]->file_id;
        }

        return $this->file->whereIn('id',$array)->select('id','path')->paginate(30);
    }

    public function changeFileNumber($number){

        if( $this->file_number->where('id',1)->first())
        return $this->file_number->where('id',1)->update([
            'file_number' => $number
        ]);
        else{
            $this->file_number->create([
                'file_number' => $number
            ]);
        }
    }
}