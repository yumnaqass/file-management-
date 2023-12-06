<?php 

namespace App\Http\Services;

use App\Http\Repositories\AdminRepository;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\FileNumberRequest;
use App\Traits\GeneralTrait;

class AdminService
{

    use GeneralTrait;

    private $repo;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->repo = $adminRepository;
    }

    public function getAllFileInSystem()
    {
        $data =  $this->repo->getAllFileInSystem();
        return $this->returnData('Data', $data);
    }

    public function getAllFileInGroup(DeleteGroupRequest $request)
    {
        $validated = $request->validated();
        $data = $this->repo->getAllFileInGroup($validated['group_id']);
        return  $this->returnData('Data', $data);
    }

    public function getAllGroupInSystem(){
        $data =   $this->repo->getAllGroupInSystem();
        return  $this->returnData('Data', $data);
    }

    public function changeFileNumber(FileNumberRequest $request){
        $validated = $request->validated();
        $this->repo->changeFileNumber($validated['number']);
        return $this->returnSuccessMessage("changed successfully");
    }
}