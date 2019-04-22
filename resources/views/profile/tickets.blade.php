@extends('layouts.app')
@section('content')
	<div class="container">
		<h1 class="text-center text-uppercase default-color font-weight-bold">Зрительские билеты</h1>
		<h1 class="text-center text-uppercase default-color font-weight-bold d-none">Покупка зрительских билетов</h1>
		<div class="row justify-content-center my-5 pt-3" id="buy_tickets_block">
			<div class="col-md-4 text-center">
				@if($tickets->count() == 0)
					<p class="text-center default-text-style">Пока у Вас нет билетов :(</p>
				@endif
				<button type="button" class="btn btn-default danger mt-2 fz-17">Купить билет</button>
				@if($tickets->count() > 0)
					<p class="text-center default-text-style" style="margin-top: 24px;">Предъявите эти электронные билеты на стойке регистрации</p>
				@endif
			</div>
		</div>
		<form action="{{ action('ProfileController@postTickets') }}" class="buy_tickets_form d-none my-5 pt-3"
		      method="post">
			@csrf
			<div class="row">
				<div class="col-md-4 offset-md-4">
					<select name="count" id="count" class="form-control{{ $errors->has('count') ? ' is-invalid' : '' }}"
					        title="Количество" required>
						@for($i = 1; $i <= 20; $i++)
							<option value="{{ $i }}">{{ $i }}</option>
						@endfor
					</select>
					@if($errors->has('count'))
						<div class="mt-2 text-center text-danger">
							Это поле обязательное
						</div>
					@endif
				</div>
				<div class="col-md-2 text-right">
					<h5 class="font-weight-bold">Сумма:</h5>
					<span id="amount" class="d-inline-block">100</span> грн
				</div>
			</div>
			<div class="row justify-content-center mt-4">
				<div class="col-md-4">
					<button type="submit" class="btn btn-default btn-block">Оплатить</button>
				</div>
			</div>
		</form>

		@foreach($tickets as $ticket)
			<div class="row justify-content-center mt-4">
				<div class="col-md-8 box">
					<ul class="list-inline">
						<li class="list-inline-item">
							<h5 class="font-weight-bold default-text-style fz-18">Билет #{{ $ticket->id }}</h5>
						</li>
						@if($ticket->is_paid)
							<li class="list-inline-item position-relative ml-3">
								<span class="bg-accept-circle rounded-circle"><i class="fa fa-check"></i></span>
								<span class="default-text-style" style="margin-left: 30px;">Мы Вас ждём</span>
							</li>
						@endif
					</ul>
					<div class="default-color secondary default-text-style mt-2 pt-4 fz-14">
						Дата создания: {{ $ticket->created_at->format('d.m.Y H:i') }}
					</div>
				</div>
			</div>
		@endforeach
	</div>
@endsection
@push('script')
	<script>
        $( function () {
            var form = $( '.buy_tickets_form' );
            $( '#buy_tickets_block' ).find( 'button' ).on( 'click', function () {
                $( '#buy_tickets_block' ).addClass( 'd-none' );
                form.removeClass( 'd-none' );
                $( 'h1' ).toggleClass( 'd-none' );
            } );
            form.find( '#count' ).on( 'change', function () {
                var count = $( this ).val(), btn = form.find( 'button:submit' );
                if ( !count ) {
                    btn.attr( 'disabled', 'disabled' );
                    form.find( '#amount' ).text( '0.00' );
                    if ( !btn.hasClass( 'invert' ) ) {
                        btn.addClass( 'invert' );
                    }
                } else {
                    var value = count * ( '{{ config('custom.ticket_price') }}' * 1 );
                    form.find( '#amount' ).text( value );
                    btn.attr( 'disabled', false ).removeClass( 'invert' );
                }
            } );
        } )
	</script>
@endpush
