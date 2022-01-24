<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;

class SendPushNotification extends Notification
{
    use Queueable;
    protected $title;
    protected $message;
    protected $data;
    protected $image;
    protected $fcmTokens;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, $fcmTokens, $data = null, $image = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->fcmTokens = $fcmTokens;
        $this->data = $data;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['firebase'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toFirebase($notifiable)
    {

        $noti = FirebaseMessage::withTitle($this->title)
            ->withBody($this->message)
            ->withImage($this->image)
            ->withAdditionalData($this->data)
            ->asNotification($this->fcmTokens); // OR ->asMessage($deviceTokens);
        return $noti;
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
