<?php


namespace App\Services\UserService;

use App\Models\User;

class UserService
{
    public function getUser(UserDTO $userData): User
    {
        if ($user = User::query()->where('email', $userData->email)->first()) {
            return $user;
        }

        return $this->createUser($userData);
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::firstWhere('email', $email);
    }

    public function createUser(UserDTO $userData): User
    {
        return User::create($userData->toArray());
    }

    public function deleteUser(User $user,){
        return User::where('id',$user->id)->delete();
    }

    public function updateUser(User $user, UserDTO $userData)
    {
        if ($userData->email == null) {
            $userData->email = $user->email;
        }

        if ($userData->password == null) {
            $userData->password = $user->password;
        }

        $user->update($userData->toArray());
    }

}
