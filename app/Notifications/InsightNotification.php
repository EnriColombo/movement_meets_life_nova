<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class InsightNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * https://github.com/laravel-notification-channels/twitter#publish-twitter-status-update-with-images
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwitterChannel::class];
    }

    /**
     * Send the notification to Twitter
     *
     * @param  mixed  $notifiable
     * @return TwitterStatusUpdate
     */
    public function toTwitter($notifiable)
    {
        if ($notifiable->hasMedia('introimage')) {
            return (new TwitterStatusUpdate($notifiable->body))->withImage('marcel.png');
            // return new TwitterStatusUpdate($post->title .' https://laravel-news.com/'. $post->uri, [$post->featured_image]);


        }

        return new TwitterStatusUpdate($notifiable->body);
    }
}
