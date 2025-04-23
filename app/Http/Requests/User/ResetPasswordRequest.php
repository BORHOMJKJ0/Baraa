<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Ichtrojan\Otp\Otp;

class ResetPasswordRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'otp' => ['required', 'numeric', 'digits:6', function ($attribute, $value, $fail) {
                $otp = new Otp;
                $isValid = $otp->validate(request('email'), $value);

                if (! $isValid->status) {
                    $fail('The provided OTP is invalid.');
                }
            }]];
    }
}
