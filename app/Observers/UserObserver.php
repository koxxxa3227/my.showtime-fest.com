<?php

namespace App\Observers;

class UserObserver {
    public function created( $user ) {
        flash('Вы успешно зарегестрированы. Письмо для создания пароля - отправлено Вам на почту');
    }
}
