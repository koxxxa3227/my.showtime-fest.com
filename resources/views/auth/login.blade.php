@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<h1 class=" mb-5 text-center text-uppercase default-color font-weight-bold">Вход</h1>
				<form method="POST" action="{{ route('login') }}">
					@csrf

					<div class="form-group row justify-content-center">

						<div class="col-md-6">
							<input id="email" type="email"
							       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
							       value="{{ old('email') }}" required autofocus placeholder="Введите email" v-model="email">

							@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
							@endif
						</div>
					</div>

					<div class="form-group row justify-content-center mt-4" v-if="!passShow">
						<div class="col-md-6">
							<input id="password" type="password"
							       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
							       name="password" required placeholder="Введите пароль" v-model="pass">

							@if ($errors->has('password'))
								<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
							@endif
						</div>
					</div>

					<div class="form-group row justify-content-center mt-4" v-if="passShow">
						<div class="col-md-6">
							<input id="password" type="text"
							       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
							       name="password" required placeholder="Введите пароль" v-model="pass">

							@if ($errors->has('password'))
								<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
							@endif
						</div>
					</div>

					<div class="form-group row justify-content-center mt-4">
						<div class="col-md-6">
							<a href="{{ route('password.request') }}" class="btn-default-link">Забыли пароль?</a>
						</div>
					</div>

					<div class="form-group row mb-0 justify-content-center mt-5 pt-3">
						<div class="col-md-6">
							<button type="submit" class="btn btn-default btn-block"
							:disabled="!email || !pass" :class="!email || !pass ? 'invert' : ''">
								Войти
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@push('script')
	<script>
        new Vue({
            el: '#app',
            data: {
                passShow: false,
                email: '',
                pass: ''
            }
        });
	</script>
@endpush
