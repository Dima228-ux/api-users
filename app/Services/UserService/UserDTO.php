<?php

namespace App\Services\UserService;

use App\Helpers\Helper;
use App\Traits\HydratesProps;
use Illuminate\Support\Facades\Hash;


class UserDTO
{
    use HydratesProps;

    public ?string $email = null;
    public ?string $name = null;
    public ?string $password = null;

    public static function fromRequest($request): UserDTO
    {
        return (new self())->hydrate($request->all());
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password) ,
        ];
    }
}
