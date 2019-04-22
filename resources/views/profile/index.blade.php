@extends('layouts.app')

@section('content')
	<div class="container">
		<h1 class="text-center text-uppercase default-color font-weight-bold">Профиль</h1>
		<form action="{{ action('ProfileController@postIndex') }}" method="post" id="form">
			@csrf
			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<input type="email" name="email" id="email"
					       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Ваш email"
					       value="{{ old('email') ?: $user->email }}" required>
					@if ($errors->has('email'))
						<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
					@endif
				</div>
			</div>
			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<input type="text" name="name" id="name"
					       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Ваше имя"
					       value="{{ old('name') ?: $user->name }}" required>
					@if ($errors->has('name'))
						<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
					@endif
				</div>
			</div>
			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<input type="text" name="surname" id="surname"
					       class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}"
					       placeholder="Ваша фамилия"
					       value="{{ old('surname')  ?: $user->surname}}" required>
					@if ($errors->has('surname'))
						<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
					@endif
				</div>
			</div>
			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<input type="tel" name="tel" id="tel"
					       class="form-control{{ $errors->has('tel') ? ' is-invalid' : '' }}"
					       placeholder="Ваш телефон"
					       value="{{ old('tel') ?: $user->tel }}" required>
					@if ($errors->has('tel'))
						<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tel') }}</strong>
                                    </span>
					@endif
				</div>
			</div>


			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<input type="text" name="country" id="country"
					       class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}"
					       value="{{ old('country') ?: $user->country }}" required>
					@if ($errors->has('country'))
						<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
					@endif
				</div>
			</div>

			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<input type="text" name="city" id="city"
					       class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
					       placeholder="Введите город"
					       value="{{ old('city') ?: $user->city }}" required>
					@if ($errors->has('city'))
						<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
					@endif
				</div>
			</div>

			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<input type="text" name="crew" id="crew"
					       class="form-control{{ $errors->has('crew') ? ' is-invalid' : '' }}"
					       placeholder="Название коллектива / crew"
					       value="{{ old('crew') ?: $user->crew }}" required>
					@if ($errors->has('crew'))
						<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('crew') }}</strong>
                                    </span>
					@endif
				</div>
			</div>
			<div class="form-group row justify-content-center mt-4">
				<div class="col-md-4">
					<select name="school" id="school" class="form-control">
						<option value="">выберите школу / студию</option>
						@foreach($schools as $school)
							<option value="{{ $school->title }}" {{ optional($user->school)->title == $school->title ? 'selected' : ''  }}>
								{{ $school->title }}
							</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group row justify-content-center mt-5 pt-3">
				<div class="col-md-4">
					<button type="submit" id="save_btn" class="btn btn-default invert btn-block" disabled>Сохранить</button>
				</div>
			</div>
		</form>
	</div>
@endsection
@push('script')
	<script>
        $( function () {
            let form = $( '#form' ),
                data = form.data();
            $( '#school' ).select2( {
                placeholder : 'Выберите школу / студию',
	            tags: true
            } );
            form.on( 'change input', function () {
                var btn = form.find( '#save_btn' );
                if ( data != $( this ).data ) {
                    btn.attr( 'disabled', false ).removeClass( 'invert' );
                } else {
                    btn.attr('disabled', 'disabled');
                    if(!btn.hasClass('invert')){
                        btn.addClass('invert');
                    }
                }
            } );
        } );
        new Vue( {
            el : '#app',
            data : {
                selected_country_id : {!! $user->country_id ?: '1' !!},
            }
        } );
	</script>
@endpush
