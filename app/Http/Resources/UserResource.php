<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isProfileEndpoind = $request->is('api/me');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'must_change_password' => (bool) $this->must_change_password,
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name ?? null,
                    ];
                });
            }),
            'created_at' => $this->when(! $isProfileEndpoind, $this->created_at->toISOString()),
            'updated_at' => $this->when(! $isProfileEndpoind, $this->updated_at->toISOString()),
        ];
    }
}
