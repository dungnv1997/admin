<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            'tenant' => $this->whenLoaded('tenant'),

            'branch' => $this->whenLoaded('branch'),


            // 'roles' => RoleResource::collection(
            //     $this->whenLoaded('roles')
            // ),

            // 'permissions' => PermissionResource::collection(
            //     $this->whenLoaded('permissions')
            // ),

            'token' => $this->api_token,
            'token_type' => 'Bearer',
        ];
    }
}
