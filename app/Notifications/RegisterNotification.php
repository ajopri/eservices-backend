<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        // dd($user->load(['role', 'group']));
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = User::find($this->user->id)->load(['role', 'group']);

        $url = url('/api/account/verify/' . $this->user->token);

        return (new MailMessage)
            ->greeting('Your e-services account has been created for you.')
            ->line('Dear, ' . $user->name)
            ->line('We want to give '.$user->group->name.' access to all transaction details with MahaChem. An account has been created with your email '.$user->email)
            ->line('Please activate it here to use our e-services:')
            ->action('Activate Account', $url)
            ->line('*This account is disabled until it’s activated by you.')
            ->markdown('vendor.notifications.activate', ['user' => $this->user]);
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
