<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UpdatePassword
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, User $user)
    {
        try {
            if (!Hash::check($fields['password'], $user->password)) {
                throw new \Exception(__('Invalid password'), Response::HTTP_BAD_REQUEST);
            }

            $user->update([
                'password' => Hash::make($fields['new_password']),
            ]);

            return $user;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
