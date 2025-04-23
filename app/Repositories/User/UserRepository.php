<?php

namespace App\Repositories\User;

use App\Models\User\User;
use App\Traits\Lockable;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserRepository
{
    use Lockable;

    public function create(array $data)
    {
        return $this->lockForCreate(function () use ($data) {
            $data['password'] = Hash::make($data['password']);

            return User::create($data);
        });
    }

    public function update(array $data)
    {
        $user = JWTAuth::user();

        return $this->lockForUpdate(User::class, $user->id, function ($lockedUser) use ($data) {
            $lockedUser->update($data);

            return $lockedUser;
        });
    }

    public function markEmailAsVerified(string $email)
    {
        $user = $this->findByEmail($email);

        return $this->lockForUpdate(User::class, $user->id, function ($lockedUser) {
            $lockedUser->update(['email_verified_at' => now()]);

            return $lockedUser;
        });
    }

    public function updatePassword(string $email, string $password)
    {
        $user = $this->findByEmail($email);

        return $this->lockForUpdate(User::class, $user->id, function ($lockedUser) use ($password) {
            $password = Hash::make($password);
            $lockedUser->update(['password' => $password]);

            return $lockedUser;
        });
    }

    public function setFcmToken(string $fcm_token)
    {
        $user = JWTAuth::user();

        return $this->lockForUpdate(User::class, $user->id, function ($lockedUser) use ($fcm_token) {
            $lockedUser->update(['fcm_token' => $fcm_token]);

            return $lockedUser;
        });
    }

    public function delete()
    {
        $user = JWTAuth::user();

        return $this->lockForDelete(User::class, $user->id, function ($lockedUser) {
            return $lockedUser->delete();
        });
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->firstOrFail();
    }
}
