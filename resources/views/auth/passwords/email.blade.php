@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<h1 class=" mb-5 text-center text-uppercase default-color font-weight-bold">Восстановление пароля</h1>

				<form method="POST" action="{{ route('password.email') }}">
					@csrf

					<div class="form-group row justify-content-center">
						<div class="col-md-6">
							@if (session('status'))
								<div class="alert alert-success" role="alert">
									{{ session('status') }}
								</div>
							@endif
							
							<input id="email" type="email"
							       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
							       value="{{ old('email') }}" required placeholder="Введите email" v-model="email">

							@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
							@endif

							<div class="mt-4 default-text-style">
								Если мы найдем аккаунт с таким email, мы вышлем ссылку для изменения пароля в письме на этот email.
							</div>
							<div class="form-group mb-0 text-center mt-5">
								<button type="submit" class="btn btn-default mt-3 px-3 btn-block" :class="!email ? 'invert' : ''" :disabled="!email">
									Сменить пароль
								</button>
							</div>
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
                email: ''
            }
        });
	</script>
@endpush
