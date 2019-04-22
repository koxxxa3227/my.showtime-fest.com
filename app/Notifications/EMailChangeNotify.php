<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EMailChangeNotify extends Notification {
    use Queueable;
    protected $verify_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $verify_code ) {
        $this->verify_code = $verify_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via( $notifiable ) {
        return [ 'mail' ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail( $notifiable ) {
        return ( new MailMessage )
            ->subject( 'Подтверждение смены почты' )
            ->greeting( 'Доброго времени суток.' )
            ->line( 'Для подтверждения смены почты - нажмите кнопку ниже' )
            ->action( 'Подтвердить изменения', action( 'ProfileController@acceptEmailChange', $this->verify_code ) );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray( $notifiable ) {
        return [
            //
        ];
    }
}
