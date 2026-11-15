<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionContoller extends Controller
{
    public function index(){
        $permissions = Permission::select('id', 'name', 'description')
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($item) => str($item->name)->before(':')->toString());

        return response()->json([
            'data' => $permissions,
        ] ,200);
    }

    public function update(UpdatePermissionRequest $request){
        $request->validated();
        $permission = Permission::find($request->id);
        if(is_null($permission)){
            return response()->json([
                'message' => 'Permission not found',
            ],401);
        }

        $permission->update($request->all());
        return response()->json([
            'message'=> 'Permission success updated',
        ],200);
    }
}
