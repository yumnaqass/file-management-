<?php


namespace App\Http\Repositories;


use App\Models\Config;
use App\Models\File;
use App\Models\File_Group;
use App\Models\Reservation;
use Illuminate\Support\Facades\Cache;

class FileRepository
{
    private $file;
    private $file_group;
    private $reservations;
    private $max_file;

    public function __construct(File $file, File_Group $group, Reservation $reservation,Config $config)
    {
        $this->file = $file;
        $this->file_group = $group;
        $this->reservations = $reservation;
        $this->max_file = $config;
    }

    public function canAddNumber($id){
        $list = $this->file->where('user_id',$id)->get();
        $Count = $list->count();
        $can = $this->max_file->first('file_number');
        if ($Count < $can->file_number)
            return true;
        else
            return false;

    }

    public function checkFileExist($file_id)
    {
        return $this->file->where('id', $file_id)
            ->exists();
    }

    public function checkFileName($name)
    {
        return $this->file->where('path', $name)
            ->exists();
    }

    public function store(array $data)
    {
        return $this->file->create($data);
    }

    public function delete($id)
    {
        $file = $this->file->where('id', $id)->first();
        $this->file->where('id', $id)->delete();

        $this->file_group->where('file_id', $id)->delete();

        if(Cache::has($id)){
            Cache::forget($id);
        }

        return $file;
    }

    public function checkOwnerFile($file_id, $user_id)
    {
        return $this->file->where('id', $file_id)
            ->where('user_id', $user_id)
            ->exists();
    }

    public function checkStateFile($file_id,$user_id)
    {
        return $this->reservations->where('file_id',$file_id)
            ->where('user_id','!=',$user_id)
            ->get();
    }

    public function checkStateFiledelete($file_id,$user_id)
    {
        return $this->reservations->where('file_id',$file_id)
           // ->where('user_id','!=',$user_id)
            ->exists();
    }

    public function checkStateFileUser($file_id,$user_id)
    {
        return $this->reservations->where('file_id', $file_id)
            ->where('user_id',$user_id)
            ->get();
    }

    public function check_outFile($file_id,$user_id)
    {
        $this->reservations->where('file_id', $file_id)
            ->where('user_id',$user_id)
            ->delete();
    }
    public function getUserCheck_In($file_id)
    {
        return $this->reservations->where('file_id', $file_id)
            ->select('id', 'file_id', 'user_id')
            ->with('user',function ($m){
                $m->select('id','name')->first();
            })
            ->first();
    }

    public function getById($id)
    {
        return $this->file->where('id', $id)->first();
    }

//    public function check_in($id)
//    {
//        return $this->file->where('id',$id)->update([
//            'status' => true,
//        ]);
//    }
//
//    public function check_out($id)
//    {
//        return $this->file->where('id',$id)->update([
//            'status' => false,
//        ]);
//    }

    public function getAllFileInGroup($group_id)
    {
        //       $this->file->where('id',$group_id)->paginate(10);
    }

    public function getAllFileCheck_InGroupForUser($group_id,$user_id){
        $a = $this->reservations->where('group_id',$group_id)->where('user_id',$user_id)->get();

        if ($a->isEmpty())
        {
            return null;
        }
  //      $array = -1;
        for ($i=0 ; $i<sizeof($a) ;$i++)
        {
            $array[$i] = $a[$i]->file_id;
        }

        return $this->file->whereIn('id',$array)->select('id','path')->get();
    }


}
