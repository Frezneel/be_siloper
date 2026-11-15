<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleType\StoreRoleTypeRequest;
use App\Http\Requests\RoleType\UpdateRoleTypeRequest;
use App\Models\RoleType;

class RoleTypeContoller extends Controller
{
    // #Create
    public function store(StoreRoleTypeRequest $request){
        $request->validated();
        $role = RoleType::create($request->all());
        
        return response()->json([
            'message' => 'Role Type '. $role->name .' Success Stored',
        ], 200);
    }

    // #Update
    public function update(UpdateRoleTypeRequest $request, RoleType $role){
        $request->validated();
        $role->update($request->validated());
        return response()->json([
            'message'=> 'Role Type '. $role->name .' Success Updated',
        ],200);
    }

    // # Get All Data tanpa pagination
    public function getAll(){
        $roles = RoleType::all();
        return response()->json([
            'Role Types' => $roles,
        ], 200);
    }

    // # Get Data by ID
    public function findId($id){
        $roleType = RoleType::find($id);
        if(is_null($roleType)){
            return response()->json([
                'message'=> 'Role Type not found'
            ], 404);
        }

        return response()->json([
            'Role Type' => $roleType,
        ], 200);
    }

    // Remove data by ID
    public function destroy($id){
        $roleType = RoleType::find($id);
        if(is_null($roleType)){
            return response()->json([
                'message'=> 'Role Type not found'
            ], 404);
        }
        
        $roleType->delete();

        return response()->json([
            'message'=> 'Role Type '. $roleType->name .' Success deleted',
        ], 200);
    }
}
