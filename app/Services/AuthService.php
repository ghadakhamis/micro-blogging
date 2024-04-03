<?php

namespace App\Services;

use App\Classes\PublicUploader;
use App\Repositories\UserRepository;
use App\Models\User;
use Hash;

class AuthService extends BaseService
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

    public function login(Array $data): User|null
    {
        $user = $this->repository->findBy('email', $data['email']);

        return ($user && Hash::check($data['password'], $user->password))? $user : null;
    }
}
