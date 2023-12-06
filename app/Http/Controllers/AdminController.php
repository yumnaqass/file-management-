<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\FileNumberRequest;
use App\Http\Services\AdminService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use GeneralTrait;

    private $service;

    public function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    public function getAllFileInSystem()
    {
        try {
            return $this->service->getAllFileInSystem();
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function getAllFileInGroup(DeleteGroupRequest $request)
    {
        try {
            return $this->service->getAllFileInGroup($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function getAllGroupInSystem()
    {
        try {
            return $this->service->getAllGroupInSystem();


        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function changeFileNumber(FileNumberRequest $request)
    {

        try {
            return $this->service->changeFileNumber($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }
}
