<?php

namespace App\Http\Resources\User;

use App\Http\Resources\CustomResource;
use App\Models\User;

class UserResource extends CustomResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->model = new User();
        $this->exceptFields = array_merge($this->exceptFields, ['password']);
		return array_merge(
			parent::toArray($request),
		);
    }
}
