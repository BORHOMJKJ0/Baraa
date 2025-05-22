<?php

namespace App\Services\User;

use App\Helpers\ResponseHelper;
use App\Http\Requests\User\EmailVerifyRequest;
use App\Http\Requests\User\ForgetPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResendOTPRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Cart\Cart;
use App\Notifications\OTPNotification;
use App\Repositories\Cart\CartRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserService
{
    public function __construct(protected UserRepository $userRepository, protected CartRepository $cartRepository) {}

    /**
     * Create new account
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $inputs = $request->except('password_confirmation');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $inputs['image'] = $path;
        }
        $user = $this->userRepository->create($inputs);
        $user->notify(new OTPNotification('emailVerify'));
        $data = [
            'user' => UserResource::make($user),
        ];

        return ResponseHelper::jsonResponse(
            $data,
            'User registered successfully! ,check your email for verification code',
            201
        );
    }

    /**
     * Account verification using a OTP
     */
    public function emailVerify(EmailVerifyRequest $request): JsonResponse
    {
        $email = $request->input('email');

        $user = $this->userRepository->markEmailAsVerified($email);

        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token)->authenticate();

        $carts = Cart::where('user_id', auth()->id())->get();
        if ($carts->isEmpty()) {
            $this->cartRepository->create();
        }

        $data = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];

        return ResponseHelper::jsonResponse($data, 'Email Verified successfully, You can use the app now');
    }

    /**
     * Log in using email and password
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $inputs = $request->all();

        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);

        if (! $token) {
            return ResponseHelper::jsonResponse([], 'Incorrect password', 401, false);
        }

        $user = JWTAuth::user();
        $this->userRepository->setFcmToken($inputs['fcm_token']);

        $data = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];
        $carts = Cart::where('user_id', auth()->id())->get();
        if ($carts->isEmpty()) {
            $this->cartRepository->create();
        }

        return ResponseHelper::jsonResponse($data, 'Logged in successfully');
    }

    /**
     * Log out of account
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->header('Authorization');
        JWTAuth::invalidate($token);

        return ResponseHelper::jsonResponse([], 'Logged out successfully!');
    }

    /**
     * Show user profile
     */
    public function getProfile(): JsonResponse
    {
        $user = JWTAuth::user();
        $data = [
            'user' => UserResource::make($user),
        ];

        return ResponseHelper::jsonResponse($data, 'Get profile successfully');
    }

    /**
     * Update user information
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $inputs = $request->all();
        $user = JWTAuth::user();
        if ($request->hasFile('image')) {
            if ($request->image && Storage::disk('public')->exists($request->image)) {
                $path = $inputs['image']->store('images', 'public');
                $inputs['image'] = $path;
            }
            if ($user->image) {
                if (Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }
            }
            $path = $inputs['image']->store('images', 'public');
            $inputs['image'] = $path;
        }
        $user = $this->userRepository->update($inputs);
        $data = [
            'user' => UserResource::make($user),
        ];

        return ResponseHelper::jsonResponse($data, 'profile updated successfully');
    }

    /**
     * Send a otp to the user to reset the password
     */
    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $email = $request->email;
        $user = $this->userRepository->findByEmail($email);
        $user->notify(new OTPNotification('resetPassword'));

        return ResponseHelper::jsonResponse([], 'Check your email for reset password!');
    }

    /**
     * Reset password using a otp
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->all();
        $this->userRepository->updatePassword($data['email'], $data['password']);

        return ResponseHelper::jsonResponse([], 'Password reset successfully!', 201);
    }

    /**
     * Resend a otp to the user to reset the password or email verification, depending on the subject value
     */
    public function resendOTP(ResendOTPRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $user = $this->userRepository->findByEmail($email);
        if (! $user) {
            return ResponseHelper::jsonResponse([], 'This email is not found', 400, false);
        }
        $user->notify(new OTPNotification($request->query('subject')));

        return ResponseHelper::jsonResponse([], 'resend OTP successfully!');
    }

    /**
     * Delete account
     */
    public function deleteAccount(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        $cart = Cart::where('user_id', auth()->id())->first();
        $this->cartRepository->delete($cart);
        $this->userRepository->delete();

        return ResponseHelper::jsonResponse([], 'Account deleted successfully!');
    }

    /**
     * Refresh jwt after expired it
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $new_token = JWTAuth::parseToken()->refresh();
        } catch (TokenInvalidException $ex) {
            return ResponseHelper::jsonResponse([], 'Invalid token', 401, false);
        } catch (TokenExpiredException $ex) {
            return ResponseHelper::jsonResponse([], 'Expired token', 401, false);
        } catch (JWTException $ex) {
            return ResponseHelper::jsonResponse([], 'Unauthenticated', 401, false);
        }
        $data = [
            'token' => $new_token,
        ];

        return ResponseHelper::jsonResponse($data, 'Token refreshed');
    }
}
