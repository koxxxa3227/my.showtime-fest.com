<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserMasterClass;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaidNotify extends Notification {
    use Queueable;
    protected $model, $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $model, $type = 'application' ) {
        $this->model = $model;
        $this->type  = $type;
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
        $model    = $this->model;
        $user     = $model->user;
        $fullName = $user->name . ' ' . $user->surname;
        $subject  = false;
        $line     = false;
        if ( $this->type == 'application' ) {
            $subject = 'Заявка оплачена';
            $line    = "Заявка #$model->id была оплачена, пользователем: $fullName";

        } elseif ( $this->type == 'ticket' ) {
            $subject = 'Билеты оплачены';
            $count   = (int) $model->amount / config( 'custom.ticket_price' );
            $line    = "Куплено $count билетов на сумму $model->amount грн, пользователем с почтой: <a href=mailto:"
                       . $user->email . ">$user->email</a>";
        } elseif ( $this->type == 'master-class' ) {
            $subject = 'Запись на мастер-класс(ы)';
            $count   = UserMasterClass::whereTransactionId( $model->id )->whereIsPaid( true )->count();
            $line    = "Запись на мастер-класс(ы), на сумму $model->amount грн, пользоваателем с почтой: <a href=mailto:"
                       . $user->email . ">$user->email</a>";
        }
        return ( new MailMessage )
            ->subject( $subject )
            ->line( $line )
            ->line( "<a href=mailto:" . $user->email . ">Написать пользователю на почту</a>" )
            ->replyTo( $user->email );
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
