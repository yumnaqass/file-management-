<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Models\User_Group;

class AuthRepository {

    private $user;

    public function __construct(User $user)
    {
        $this->user =  $user;
    }

    public function register(array $data)
    {
        return $this->user->create($data);
    }

    public function login(array $data)
    {
        return $this->user->where('email', $data['email'])->first();
    }

}