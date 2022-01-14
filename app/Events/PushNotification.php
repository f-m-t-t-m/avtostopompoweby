<?php

namespace App\Events;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class PushNotification extends Notification {
    public $section;
    public $subject;

    public function __construct($section, $subject) {
        $this->section = $section;
        $this->subject = $subject;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }
    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Новое сообщение')
                ->setBody(sprintf('Новое сообщение в разделе: %s предмета %s',
                    $this->section->name,
                    $this->subject->name)));
    }
}
