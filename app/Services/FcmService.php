<?php

namespace App\Services;

use App\Repositories\NotificationRepository;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    protected $messaging;

    protected $userRepository;

    protected $storeRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->userRepository = $userRepository;
        $this->storeRepository = $storeRepository;
        $this->notificationRepository = $notificationRepository;
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

        $this->messaging = $firebase->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body, array $data = [])
    {
        \Log::info($deviceToken);
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification)
            ->withData($data);
        \Log::info($body);

        return $this->messaging->send($message);
    }
}
