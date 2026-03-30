<?php

namespace App\Repositories;


use A17\Twill\Repositories\ModuleRepository;
use App\Models\User;

class UserRepository extends ModuleRepository
{
    

    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
