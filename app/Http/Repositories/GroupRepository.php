<?php


namespace App\Http\Repositories;


use App\Models\File_Group;
use App\Models\Group;
use App\Models\Reservation;
use App\Models\User;
use App\Models\User_Group;

class GroupRepository
{
    private $group, $user;
    private $group_file, $group_user;
    private $reservations;

    public function __construct(User $user, Group $group, File_Group $file_group, Reservation $reservations, User_Group $group_user)
    {
        $this->group = $group;
        $this->user = $user;
        $this->group_file = $file_group;
        $this->reservations = $reservations;
        $this->group_user = $group_user;
    }

    public function createGroup(array $data)
    {
        return $this->group->create($data);
    }

    public function AddFileToGroup(array $data)
    {
        $this->group_file->create($data);
    }

    public function checkFileExistsInGroup($group_id, $file_id)
    {
        return $this->group_file->where('file_id', $file_id)
            ->where('group_id', $group_id)
            ->exists();
    }

    public function checkStateFileInGroup($group_id, $file_id)
    {
        return $this->reservations->where('file_id', $file_id)
            ->where('group_id', $group_id)
            ->exists();
    }

    public function deleteFileFormGroup($group_id, $file_id)
    {
        return $this->group_file->where('file_id', $file_id)
            ->where('group_id', $group_id)
            ->delete();
    }


    public function checkUserExistsInGroup($group_id, $user_id)
    {
        return $this->group_user->where('user_id', $user_id)
            ->where('group_id', $group_id)
            ->exists();
    }

    public function checkUserExists($user_id)
    {
        return $this->user->where('id', $user_id)
            ->exists();
    }

    public function addUserToGroup(array $data)
    {
        return $this->group_user->create($data);
    }

    public function checkOwnerGroup($group_id, $owner_id)
    {
        return $this->group->where('owner_id', $owner_id)
            ->where('id', $group_id)
            ->exists();
    }

    public function getUserReservationInGroup($group_id, $user_id)
    {
        return $this->reservations->where('user_id', $user_id)
            ->where('group_id', $group_id)
            ->get();
    }

    public function checkUserReservationInGroup($group_id, $user_id)
    {
        return $this->reservations->where('user_id', $user_id)
            ->where('group_id', $group_id)
            ->exists();
    }
    public function deleteUserInGroup($group_id, $user_id)
    {
        return $this->group_user->where('user_id', $user_id)
            ->where('group_id', $group_id)
            ->delete();
    }

    public function deleteGroup($group_id){
        $this->group->where('id',$group_id)
            ->delete();

        return $this->group_user->where('group_id', $group_id)
            ->delete();
    }

    public function checkReservationInGroup($group_id)
    {
        return $this->reservations->where('group_id', $group_id)
            ->exists();
    }

    public function getAllUserInGroup($group_id){
        return $this->group_user->where('group_id',$group_id)
            ->with('user',function ($m){
                $m->select('id','name');
            })
            ->select('id','user_id')
            ->get();
    }
}
