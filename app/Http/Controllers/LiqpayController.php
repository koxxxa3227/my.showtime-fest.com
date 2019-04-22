<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserMasterClass;
use App\Models\UserTicket;
use App\Notifications\PaidNotify;
use Illuminate\Http\Request;
use LiqPay;

class LiqpayController extends Controller {
    public function pay( $id, $type = 'application' ) {
        $view = view( 'liqpay.pay' );

        $private_key = config( 'services.liqpay.private_key' );
        $public_key  = config( 'services.liqpay.public_key' );

        $liqpay     = new LiqPay( $public_key, $private_key );
        $model      = false;
        $server_url = false;
        $amount     = 0;

        if ( $type == 'application' ) {

            $model  = Application::whereAccepted( false )->whereIsPaid( false )->findOrFail( $id );
            $amount = $model->category->price * $model->participants->count();

        } elseif ( $type == 'ticket' ) {

            $model  = Transaction::whereIsPaid( false )->findOrFail( $id );
            $amount = $model->amount;

        } elseif ( $type == 'master-class' ) {

            $model  = Transaction::whereIsPaid( false )->findOrFail( $id );
            $amount = $model->amount;

        }

        $html = $liqpay->cnb_form(
            array(                // Создание формы, которая выводиться во вьюшке, через {!! $html !!}
                                  'action'      => 'pay',
                                  'amount'      => $amount,
                                  'currency'    => 'UAH',
                                  'description' => "Оплата транзакции #$id",
                                  'order_id'    => $id . '-' . time(),
                                  // к айди платежа, через - добавленям время ф-цией time()
                                  'version'     => '3',
                                  'server_url'  => action( 'LiqpayController@status', $type ),
                                  'result_url'  => action( 'LiqpayController@result', $type ),
                                  //                                  'sandbox'     => 1, // Тестовый платёж
            ) );

        $view->html = $html;

        //        $user = \Auth::user();

        return $view;
    }

    public function status( Request $request, $type ) {
        $private_key = config( 'services.liqpay.private_key' );

        $sign = base64_encode( sha1(  // После получения ответа с сервера, кодируем результат
                                   $private_key .
                                   $request->data .
                                   $private_key
                                   , 1 ) );

        \Log::info( 'liqpay/signature', [ // Помещаем информацию о статусе в логи
                                          'sign_1' => $sign,
                                          'sign_2' => $request->signature
        ] );

        if ( $sign == $request->signature ) { // если закодированные данные совпадают с пришедшей сигнатурой тогда делаем следующее
            $data = base64_decode( $request->data ); // декодируем результат

            \Log::info( 'liqpay/status', [ // Помещаем информацию о статусе в логи
                                           'ip'     => $request->ip(),
                                           'method' => $request->method(),
                                           'data'   => $data
            ] );

            $data = json_decode( $data );  // преобразовываем результат, который пришел в формате json , в объект

            if ( $data->action == 'pay' && $data->type == 'buy' ) { // если подписи верны тогда делаем следующее.
                list( $order_id, $time ) = explode( '-', $data->order_id ); // Разбиваем вернувшийся айди на две переменные.

                if ( $type == 'application' ) {

                    $model = Application::findOrFail( $order_id );
                    if ( $data->status == 'success' ) {
                        //                    $model->accepted = true;
                        $model->is_paid = true;
                        $model->save();

                        \Notification::route( 'mail', [ 'borovikov.s@gmail.com', 'denis.mirgoyazov@gmail.com' ] )
                                     ->notify( new PaidNotify( $model ) );

                    }
                } elseif ( $type == 'ticket' ) {
                    $model = Transaction::findOrFail( $order_id );
                    if ( $data->status == 'success' ) {
                        $model->is_paid = true;
                        $model->save();

                        UserTicket::whereTransactionId( $order_id )->update( [
                                                                                 'is_paid' => true
                                                                             ] );

                        \Notification::route( 'mail', [ 'borovikov.s@gmail.com', 'denis.mirgoyazov@gmail.com' ] )
                                     ->notify( new PaidNotify( $model, 'ticket' ) );
                    }

                } elseif ( $type == 'master-class' ) {
                    $model = Transaction::findOrFail( $order_id );
                    if ( $data->status == 'success' ) {
                        $model->is_paid = true;
                        $model->save();

                        UserMasterClass::whereTransactionId( $order_id )->update( [
                                                                                      'is_paid' => true
                                                                                  ] );

                        \Notification::route( 'mail', [ 'borovikov.s@gmail.com', 'denis.mirgoyazov@gmail.com' ] )
                                     ->notify( new PaidNotify( $model, 'master-class' ) );
                    }
                }
            }
        }
    }

    public function result( $type ) {
        $url = false;
        if ( $type == 'application' ) {
            $url = redirect()->action( 'ProfileController@applications' );
        } elseif ( $type == 'ticket' ) {
            $url = redirect()->action( 'ProfileController@tickets' );
        } elseif ( $type == 'master-class' ) {
            $url = redirect()->action( 'ProfileController@masterClasses' );
        }
        return $url;
    }
}
