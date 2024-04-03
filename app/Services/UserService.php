<?php

namespace App\Services;

use App\Classes\PublicUploader;
use App\Repositories\UserRepository;
use App\Models\User;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function register(Array $data): User
    {
        if (isset($data['image'])) {
            $data['image'] = app(PublicUploader::class)->upload($data['image'], 'profiles');
        }

        return $this->repository->create($data);
    }
}
