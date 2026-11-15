<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function resetPassword(User $user){
        if (request()->user()->id === $user->id){
            return response()->json([
                "message"=> "You cannot reset password your own account."
            ], 422);
        }
        $tempPassword = Str::random(8);

        $user->update([
            'password' => Hash::make($tempPassword),
            'must_change_password' => true,
        ]);

        $user->tokens()->delete();
        
        return response()->json([
            'message' => "Password for user {$user->name} has been reset successfully.",
            'temporary_password' => $tempPassword 
        ]);
    }

    public function create(CreateUserRequest $request){
        $validated = $request->validated();
        
        $user = DB::transaction(function () use($validated){
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'must_change_password' => $validated['must_change_password'] ?? true,
            ]); 

            if(!empty($validated['roles'])){
                $pivotData = [];

                foreach ($validated['roles'] as $roleItem){
                    $pivotData[$roleItem['role_id']] = [
                        'is_active' => $roleItem['is_active'] ?? true,
                        'start_date'=> $roleItem['start_date'] ?? now()->toDateString(),
                        'end_date'=>$roleItem['end_date'] ?? null,
                    ];
                }

                $user->roles()->attach($pivotData);
            }

            return $user;
        });
        return response()->json([
            'message' => 'User Registered Successfully.',
            'data' => new UserResource($user->load('roles')),
        ], 201);
    }

    public function index(Request $request){
        $perPage = min(max((int) $request->input('per_page', 15), 1), 100);
        $search = $request->input('search');

        $user = User::query()
            ->with('roles')
            ->when($search, function($query, $search){
                $term = '%'. mb_strtolower($search) .'%';
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term]);
                });
            })
            ->latest()
            ->paginate($perPage);
        
        return UserResource::collection($user);
    }

    public function getById(User $user){
        return new UserResource($user->load('roles'));
    }

    public function update(UpdateUserRequest $request, User $user){
        $validated = $request->safe()->except(['password', 'must_change_password']);

        DB::transaction(function () use ($user, $validated){
            $profileData = Arr::only($validated, ['name', 'email']);
            if (!empty($profileData)) {
                $user->update($profileData);
            }

            if (array_key_exists('roles', $validated)){
                $pivotData = [];
                foreach($validated['roles'] as $roleItem){
                    $pivotData[$roleItem['role_id']] = [
                        'is_active' => $roleItem['is_active'] ?? true,
                        'start_date' => $roleItem['start_date'] ?? now()->toDateString(),
                        'end_date' => $roleItem['end_date'] ?? null,
                    ];
                }

                $changes = $user->roles()->sync($pivotData);

                //Jangan lupa cabut token karena update role
                if (!empty($changes['attached']) || !empty($changes['detached']) || !empty($changes['updated'])) {
                    $user->tokens()->delete();
                }
            }

        });

        return response()->json([
            'message'=> 'User updated successfully.',
            'data'=> new UserResource($user->fresh('roles')),
        ], 200);
    }

    public function destroy(Request $request, User $user){
        if($request->user()->id === $user->id){
            return response()->json([
                'message' => 'You cannot delete your own account.',
            ], 422);            
        }

        $user->tokens()->delete();
        $user->delete();
        
        return response()->json([
            'message' => 'User deleted successfully.',
        ],200);
    }

}
