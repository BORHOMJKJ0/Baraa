<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EmailVerifyRequest;
use App\Http\Requests\User\ForgetPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResendOTPRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     description="Enter JWT Bearer token in the format 'Bearer {token}'"
     * )
     */
    /**
     * @OA\Post(
     *     path="/users/register",
     *     summary="Create a new user account",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                  type="object",
     *                  required={"first_name", "last_name", "email", "password", "password_confirmation","location"},
     *
     *                  @OA\Property(property="first_name", type="string", example="Nour alden"),
     *                  @OA\Property(property="last_name", type="string", example="Khlil"),
     *                  @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="1234567890"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", example="1234567890"),
     *                  @OA\Property(property="location", type="string", example="Damascus"),
     *                  @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully! ,check your email for verification code"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="first_name", type="string", example="Nour alden"),
     *                     @OA\Property(property="last_name", type="string", example="Khlil"),
     *                     @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com"),
     *                       @OA\Property(property="location", type="string", example="Damascus"),
     *                     @OA\Property(property="image", type="string", format="url", example="https://ip:8000/storage/images/photo.jpg")
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="email", type="string", example="The email has already been taken."),
     *                 @OA\Property(property="password", type="string", example="The password field confirmation does not match."),
     *                 @OA\Property(property="password_confirmation", type="string", example="The password confirmation field must match password."),
     *                 @OA\Property(property="app_password", type="string", example="The selected app password is invalid.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        return $this->userService->register($request);
    }

    /**
     * @OA\Post(
     *     path="/users/emailVerify",
     *     summary="Account verification using an OTP",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Account verification information",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email", "otp"},
     *
     *                 @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com"),
     *                 @OA\Property(property="otp", type="string", example="123456")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Email Verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Email Verified successfully, You can use the app now"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="first_name", type="string", example="Abood"),
     *                     @OA\Property(property="last_name", type="string", example="Sarhan"),
     *                     @OA\Property(property="location", type="string", example="Damascus"),
     *                     @OA\Property(property="email", type="string", example="habuazan@gmail.com"),
     *                     @OA\Property(property="image", type="string", example=null)
     *                 ),
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *             ),
     *              @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *      ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="otp", type="string", example="The provided OTP is invalid.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     )
     * )
     */
    public function emailVerify(EmailVerifyRequest $request)
    {
        return $this->userService->emailVerify($request);
    }

    /**
     * @OA\Post(
     *     path="/users/login",
     *     summary="Log in using email and password",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login information",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email", "password"},
     *
     *                 @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com"),
     *                 @OA\Property(property="password", type="string", example="1234567890"),
     *                 @OA\Property(property="fcm_token", type="string", example="dpTcAzVoQFil-nyJYaTJd7:APA91bGmhBPVV1MtbKFGrYcIqyMaEycdGXPzOgahYSfM3iLaYQTXQGSG5D-YomRDo7-VqvxWsJOYTYYG3Ae_btPHnZZ_b6XoNahkdo7B5bO4sk-I6AeP7_Q"),
     *                 @OA\Property(property="remember_me", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logged in successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged in successfully"),
     *             @OA\Property(property="status_code", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="first_name", type="string", example="Omar"),
     *                     @OA\Property(property="last_name", type="string", example="Borhom"),
     *                     @OA\Property(property="email", type="string", example="habuazan@gmail.com"),
     *                       @OA\Property(property="location", type="string", example="Damascus"),
     *                     @OA\Property(property="image", type="string", format="url", example="http://127.0.0.1:8000/storage/images/tfg43fGPSvMYQ3AGZsJFtYTrqCxibGHSiaRSaZpk.png")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Incorrect password",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Incorrect password"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="status_code", type="integer", example=400),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="email", type="string", example="The selected email is invalid."),
     *                 @OA\Property(property="remember_me", type="string", example="The selected remember me is invalid.")
     *             )
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        return $this->userService->login($request);
    }

    /**
     * @OA\Post(
     *     path="/users/logout",
     *     summary="Log out of account",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=false,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }

    /**
     * @OA\Get(
     *     path="/users/profile",
     *     summary="Show user profile",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get profile successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Get profile successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="first_name", type="string", example="Omar"),
     *                     @OA\Property(property="last_name", type="string", example="Borhom"),
     *                       @OA\Property(property="location", type="string", example="Damascus"),
     *                     @OA\Property(property="email", type="string", example="habuazan@gmail.com"),
     *                     @OA\Property(property="image", type="string", format="url", example="http://127.0.0.1:8000/storage/images/tfg43fGPSvMYQ3AGZsJFtYTrqCxibGHSiaRSaZpk.png")
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function getProfile()
    {
        return $this->userService->getProfile();
    }

    /**
     * @OA\Post(
     *     path="/users/update",
     *     summary="Update user information",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Update Profile Data",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="first_name", type="string", example="Nour alden"),
     *                 @OA\Property(property="last_name", type="string", example="khlil"),
     *                       @OA\Property(property="location", type="string", example="Damascus"),
     *                 @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="first_name", type="string", example="nour"),
     *                     @OA\Property(property="last_name", type="string", example="Uncle"),
     *                     @OA\Property(property="location", type="string", example="Damascus"),
     *                    @OA\Property(property="email", type="string", example="habuazan@gmail.com"),
     *                     @OA\Property(property="image", type="string", format="url", example="http://127.0.0.1:8000/storage/images/V9DsnWtuMGaiQUKng05Zavz5NaGNL6qSFewAGzLn.png")
     *                 )
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="first_name", type="string", example="The first_name field format is invalid."),
     *                 @OA\Property(property="last_name", type="string", example="The last_name field format is invalid.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        return $this->userService->updateProfile($request);
    }

    /**
     * @OA\Post(
     *     path="/users/forgetPassword",
     *     summary="Send an OTP to the user to reset the password",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User email",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Check your email for reset password",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Check your email for reset password!"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="email", type="string", example="The selected email is invalid.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     )
     * )
     */
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        return $this->userService->forgetPassword($request);
    }

    /**
     * @OA\Post(
     *     path="/users/resetPassword",
     *     summary="Reset password using an OTP",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email, new password, password confirmation, and OTP",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="1234567890"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", example="1234567890"),
     *                 @OA\Property(property="otp", type="integer", example=123456)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Password reset successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password reset successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="email", type="string", example="The selected email is invalid."),
     *                 @OA\Property(property="password", type="string", example="The password field confirmation does not match."),
     *                 @OA\Property(property="otp", type="string", example="The otp field must be a number.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->userService->resetPassword($request);
    }

    /**
     * @OA\Post(
     *     path="/users/resendOTP",
     *     summary="Resend an OTP for password reset or email verification",
     *     tags={"User"},
     *
     *     @OA\Parameter(
     *         name="subject",
     *         in="query",
     *         required=true,
     *         description="Notification subject (Allowed values: resetPassword, emailVerify)",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"resetPassword", "emailVerify"}
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User email",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="email", type="string", example="khlilnoor0@gmail.com")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Resend OTP successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Resend OTP successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="email", type="string", example="The selected email is invalid."),
     *                 @OA\Property(property="subject", type="string", example="The selected subject is invalid.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=400)
     *         )
     *     )
     * )
     */
    public function resendOTP(ResendOTPRequest $request)
    {
        return $this->userService->resendOTP($request);
    }

    /**
     * @OA\Delete(
     *     path="/users/delete",
     *     summary="Delete user account",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Account deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Account deleted successfully!"),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="successful", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="status_code", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function deleteAccount()
    {
        return $this->userService->deleteAccount();
    }

    /**
     * @OA\Post(
     *     path="/users/refreshToken",
     *     summary="Refresh JWT after expiration",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Old JWT token",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="successful", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Token refreshed"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="token", type="string", example="new.jwt.token.here")
     *              ),
     *              @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized responses",
     *
     *         @OA\JsonContent(
     *             oneOf={
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(property="successful", type="boolean", example=false),
     *                     @OA\Property(property="message", type="string", example="Unauthenticated"),
     *                     @OA\Property(property="status_code", type="integer", example=401)
     *                 ),
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(property="successful", type="boolean", example=false),
     *                     @OA\Property(property="message", type="string", example="Invalid token"),
     *                     @OA\Property(property="status_code", type="integer", example=401)
     *                 ),
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(property="successful", type="boolean", example=false),
     *                     @OA\Property(property="message", type="string", example="Expired token"),
     *                     @OA\Property(property="status_code", type="integer", example=401)
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function refreshToken(Request $request)
    {
        return $this->userService->refreshToken($request);
    }
}
