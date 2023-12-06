<?php


namespace App\Http\Repositories;


use App\Models\File;
use App\Models\User;
use App\Models\File_Group;
use App\Models\User_Group;

class DisplayRepository
{
    private $file, $group_user, $group, $user;

    public function __construct(File $file, User_Group $group_user, File_Group $group, User $user)
    {
        $this->file = $file;
        $this->group_user = $group_user;
        $this->group = $group;
        $this->user = $user;
    }

    public function getMyFile($user_id)
    { 
        return $this->file->where('user_id', $user_id)->get();
            //->paginate(15);
    }

    public function getGroupFile($group_id)
    {
        $array = $this->group->where('group_id', $group_id)->get('file_id');

        if ($array->isEmpty()) {
            $file[0] = -5;
        }

        for ($i = 0; $i < sizeof($array); $i++) {
            $file[$i] = $array[$i]->file_id;
        }

        return $this->file->whereIn('id', $file)
            ->select('id', 'path')
            ->paginate(15);
    }

    public function getMyGroup($user_id)
    {
        return $this->group_user->where('user_id', $user_id)
            ->with('group', function ($m) {
                $m->select('id', 'name');
            })
            ->paginate(15);
    }

    public function getAllUserInSystem()
    {
        return $this->user->select('id','name','email')->paginate(30);
    }


}
