<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FileRepository;
use App\Http\Repositories\GroupRepository;
use App\Http\Repositories\HistoryRepository;
use App\Http\Requests\AddFileToGroupRequest;
use App\Http\Requests\AddUserToGroupRequest;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Services\GroupServices;
use App\Models\UserGroup;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class GroupController extends Controller
{
    use GeneralTrait;
    private $service;

    public function __construct(GroupServices $groupServices)
    {
        $this->service = $groupServices;

    }

    public function CreateGroup(CreateGroupRequest $request)
    {
        try {
            return $this->service->CreateGroup($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }

    }

    public function AddFileToGroup(AddFileToGroupRequest $request){

        try {
            return $this->service->AddFileToGroup($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }

    }

    public function deleteFileFromGroup(AddFileToGroupRequest $request){
        try {
            return $this->service->deleteFileFromGroup($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }

    }


    public function addUserToGroup(AddUserToGroupRequest $request){
        try {
            return $this->service->addUserToGroup($request);
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function deleteUserFromGroup(AddUserToGroupRequest $request){
        try {
            return $this->service->deleteUserFromGroup($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function deleteGroup(DeleteGroupRequest $request){
        try {
            return $this->service->deleteGroup($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }



}
