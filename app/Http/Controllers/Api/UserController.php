<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService\UserDTO;
use App\Services\UserService\UserService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function signUp(SignUpRequest $request){

        $user = $this->userService->createUser(UserDTO::fromRequest($request));
        return response()->json(['user' => UserResource::make($user)]);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if (! Hash::check($request->old_password, $user?->password)) {
            return response()->json(['error' => 'wrong password'], 400);
        }

        if ($user  && ($request->email || $request->password)) {
            $this->userService->updateUser($user, UserDTO::fromRequest($request));
            return response()->json(['user' => UserResource::make($user)]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function deleteUser(){
        if ($user = auth()->user()) {
            $status =  ($this->userService->deleteUser($user))?'success':'error';
            return response()->json(['status' => $status]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function signOut(){
        if ($user = auth()->user()) {
            if ($user->tokens) {
                $user->tokens()->delete();
            }
            return response()->json();
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function getUser()
    {
        if ($user = auth()->user()) {
            return response()->json(['user' => UserResource::make($user)]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function signIn(SignInRequest $request)
    {
        if (! $user = $this->userService->getUserByEmail($request->email)) {
            return response()->json(['error' => 'user not found'], 400);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'wrong password'], 400);
        }

        return $this->authUser($user);
    }

    private function authUser(User $user)
    {
        if ($user->tokens) {
            $user->tokens()->delete();
        }
        $token = $user->createToken('authToken');

        return response()
            ->json(['token' => $token->plainTextToken]);;
    }
}
