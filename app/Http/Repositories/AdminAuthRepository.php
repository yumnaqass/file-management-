<?php 

namespace App\Http\Repositories;

use App\Models\Admin;
use App\Traits\GeneralTrait;

class AdminAuthRepository
{

    private $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function register(array $data)
    {
        return $this->admin->create($data);
    }

    public function login(array $data)
    {
        return $this->admin->where('email', $data['email'])->first();
    }
}