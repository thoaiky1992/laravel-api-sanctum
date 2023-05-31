<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends CrudController
{
    public function __construct(Request $request)
	{
		parent::__construct(
			new User(),
			UserResource::class,
			UserRequest::class
		);
		$this->request = $request;
	}

    public function scope() {
        return auth()->user();
    }
}
