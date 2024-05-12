<?php

namespace App\Services;

use App\Http\Requests\UsersRequest;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Request;

class UserValidator
{
    public function validateUserRequest(Request $request): string
    {
        $usersRequest = new UsersRequest();
        $validatorFactory = app(ValidatorFactory::class);
        if (!$request->query('id')) {
            return 'id requerida en la URL';
        }
        $data = array(
            'id' => $request->query('id'),
        );
        $validator = $validatorFactory->make($data, $usersRequest->rules());
        if ($validator->fails()) {
            return 'id no es numerica en la URL';
        }
        return 'id correcta';
    }
}
