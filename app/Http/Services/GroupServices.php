<?php
 

namespace App\Http\Services;


use App\Http\Repositories\FileRepository;
use App\Http\Repositories\GroupRepository;
use App\Http\Repositories\HistoryRepository;
use App\Http\Requests\AddFileToGroupRequest;
use App\Http\Requests\AddUserToGroupRequest;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Traits\GeneralTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class GroupServices
{  
    use GeneralTrait;

    private $repo;
    private $repo_file, $log;

    public function __construct(GroupRepository $groupRepository, FileRepository $fileRepository, HistoryRepository $historyRepository)
    {
        $this->repo = $groupRepository;
        $this->repo_file = $fileRepository;
        $this->log = $historyRepository;
    }

    public function CreateGroup(CreateGroupRequest $request)
    {
        $validated = $request->validated();

        $user = JWTAuth::parseToken()->authenticate();

        $validated['owner_id'] = $user->id;
        $group = $this->repo->createGroup($validated);
        $validated['user_id'] = $user->id;
        $validated['group_id'] = $group->id;
        $this->repo->addUserToGroup($validated);
        return $this->returnSuccessMessage('created successfully');
    }

    public function AddFileToGroup(AddFileToGroupRequest $request)
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();


        if ($this->repo->checkFileExistsInGroup($validated['group_id'], $validated['file_id'])) {
            return $this->returnError(403, 'the file exists in this group');
        }

        if (!$this->repo_file->checkFileExist($validated['file_id'])) {
            return $this->returnError(403, "can not find file");
        }
        $this->repo->AddFileToGroup($validated);
        $this->log->addOperation($validated['file_id'], "addes to group", $user->id);
        return  $this->returnSuccessMessage('added successfully');
    }

    public function deleteFileFromGroup(AddFileToGroupRequest $request)
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        if (!$this->repo_file->checkOwnerFile($validated['file_id'], $user->id)) {
            return $this->returnError(403, "you not owner this file");
        }

        if ($this->repo->checkStateFileInGroup($validated['group_id'], $validated['file_id'])) {
            return $this->returnError(403, "the file check_in status in this group");
        }
        $this->repo->deleteFileFormGroup($validated['group_id'], $validated['file_id']);
        $this->log->addOperation($validated['file_id'], "delete from group", $user->id);
        return $this->returnSuccessMessage('deleted successfully');
    }

    public function addUserToGroup(AddUserToGroupRequest $request)
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        if (!$this->repo->checkOwnerGroup($validated['group_id'], $user->id)) {
            return $this->returnError(403, "you not owner the group");
        }

        if ($this->repo->checkUserExistsInGroup($validated['group_id'], $validated['user_id'])) {
            return $this->returnError(403, "the user exists in this group");
        }

        if (!$this->repo->checkUserExists($validated['user_id'])) {
            return $this->returnError(403, "can not find user");
        }
        $this->repo->addUserToGroup($validated);
        return $this->returnSuccessMessage('added user successfully');
    }

    public function deleteUserFromGroup(AddUserToGroupRequest $request)
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        if (!$this->repo->checkOwnerGroup($validated['group_id'], $user->id)) {
            return $this->returnError(403, "you not owner the group");
        }

        if ($this->repo->checkUserReservationInGroup($validated['group_id'], $validated['user_id'])) {
            return $this->returnError(403, "can not delete the user now");
        }
        $this->repo->deleteUserInGroup($validated['group_id'], $validated['user_id']);
        return  $this->returnSuccessMessage('deleted user successfully');
    }

    public function deleteGroup(DeleteGroupRequest $request)
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        if (!$this->repo->checkOwnerGroup($validated['group_id'], $user->id)) {
            return $this->returnError(403, "you not owner the group");
        }

        if ($this->repo->checkReservationInGroup($validated['group_id'])) {
            return $this->returnError(403, "can not delete the group now");
        }
        $this->repo->deleteGroup($validated['group_id']);
        return   $this->returnSuccessMessage('deleted group successfully');
    }
}
