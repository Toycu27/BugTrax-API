<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserFileRequest;
use App\Http\Traits\JsonResponseTrait;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use JsonResponseTrait;

    public function show(UserRequest $request, User $user): JsonResponse
    {
        $withArr = $request->with ? explode(',', $request->with) : [];

        return $this->simpleResponse(true, null, $user->load($withArr));
    }

    public function index(UserRequest $request): JsonResponse
    {
        $users = User::class;

        $orderCount = 0;
        if ($request->sort ?? false) {
            foreach($request->sort AS $field => $order) {
                if (in_array($field, User::$sortable)) {
                    if ($orderCount === 0) $users = User::orderBy($field, $order === 'ASC' ? 'ASC' : 'DESC');
                    else $users->orderBy($field, $order === 'ASC' ? 'ASC' : 'DESC');
                    $orderCount++;
                }
            }
        }
        if ($orderCount === 0) $users = User::latest('id');

        if ($request->name ?? false) $users->where('name', 'like', '%' . $request->name . '%');
        
        if ($request->with ?? false) $users->with(explode(',', $request->with));

        if ($request->paginate ?? false) 
            return $this->ResponseWithPagination(true, null, $users->paginate($request->paginate)->withQueryString());
        else 
            return $this->simpleResponse(true, null, $users->get());
    }

    public function storeAvatar(UserFileRequest $request): JsonResponse
    {
        $loggedInUser = $request->user();

        if ($request->hasFile('avatar')) {
            $uploadedFile = $request->file('avatar');
            $path = $request->file('avatar')->storeAs(
                'avatars',
                $request->user()->id . '.' .$uploadedFile->guessExtension(),
                'public'
            );
            if ($path) {
                $loggedInUser->avatar_path = $path; 
            }
        }

        $success = $loggedInUser->update();
        return $this->simpleResponse($success, 'Your Avatar has been updated.', $loggedInUser);
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $loggedInUser = $request->user();

        //Demo user is not allowed to change account information
        if ($loggedInUser->name == 'demo') {
            return $this->errorResponse('Demo User is not allowed to change Account information.');
        }

        //Only allow Role Update if logged in User is Admin
        if ($request->role && $loggedInUser->role === 'Admin') {
            $user->role = $request->role;

            $success = $user->update();
            return $this->simpleResponse($success, 'User Role has been updated.', $user);
        } else {
            if ($request->timezone) $loggedInUser->timezone = $request->timezone;
            if ($request->name) $loggedInUser->name = $request->name;
            if ($request->password) $loggedInUser->password = Hash::make($request->password);
            
            $success = $loggedInUser->update();
            return $this->simpleResponse($success, 'Your Account information has been updated.', $loggedInUser);
        }
    }

    public function destroy(UserRequest $request, User $user): JsonResponse
    {
        $user->destroy($user->id);

        return $this->simpleResponse(true, 'Your Account has been deleted.');
    }
}
