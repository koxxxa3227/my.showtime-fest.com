<?php

function flash($msg, $type = 'success'){
    Session::flash( 'flash', $msg );
	Session::flash( 'flash-type', $type );
}

function secToMin( $value ) {
    $minutes = (int) ($value / 60);
    $seconds = $value % 60;
    $seconds = $seconds < 10 ? '0'.$seconds : $seconds;

    return $minutes . ':' . $seconds;
}
function date_formatter($date){
    return \Carbon\Carbon::parse($date)->format('d.m.Y H:i');
}