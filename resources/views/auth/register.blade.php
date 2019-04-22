@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<h1 class="default-color text-center text-uppercase font-weight-bold">Регистрация</h1>

				<div class="card-body">
					<form method="POST" action="{{ route('register') }}">
						@csrf

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<input id="email" type="email"
								       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
								       required autofocus placeholder="Введите ваш email"
								       value="{{ old('email') }}">

								@if ($errors->has('email'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<input id="name" type="text"
								       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
								       required autofocus placeholder="Введите ваше имя"
								       value="{{ old('name') }}">

								@if ($errors->has('name'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<input id="surname" type="text"
								       class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}"
								       name="surname"
								       required autofocus
								       placeholder="Введите вашу фамилию" value="{{ old('surname') }}">

								@if ($errors->has('surname'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<input id="tel" type="tel"
								       class="form-control{{ $errors->has('tel') ? ' is-invalid' : '' }}"
								       name="tel"
								       required autofocus
								       placeholder="Введите ваш телефон" value="{{ old('tel') }}">

								@if ($errors->has('tel'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tel') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<input type="text" name="country" id="country" class="form-control"
								       placeholder="Введите страну" value="{{ old('country') }}">
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<input type="text" name="city" id="city" class="form-control"
								       placeholder="Введите город" value="{{ old('city') }}">
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<input id="crew" type="text"
								       class="form-control{{ $errors->has('crew') ? ' is-invalid' : '' }}"
								       name="crew"
								       required autofocus
								       placeholder="Введите название коллектива / crew" value="{{ old('crew') }}">

								@if ($errors->has('crew'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('crew') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="row form-group justify-content-center mt-5">
							<div class="col-md-6 default-color danger default-text-style">
								Очень важно правильно выбрать студию / школу, для того что бы суммировались очки в номинации
								<a href="http://showtime-fest.com/best-dance-studio/" target="_blank"
								   class="btn-default-link">BEST STUDIO</a>. Для этого у вас есть возможность ввести название своей студии / школы, а так же выбрать из списка уже добавленых, начните вводить название и если оно уже есть в системе, название выпадет в низу. Если нет - смело добавляете новое название.
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4">
							<div class="col-md-6">
								<select name="school" id="school" class="form-control">
									<option value=""></option>
									@foreach($schools as $school)
										<option value="{{ $school->title }}" {{ old('school') == $school->title ? 'selected' : '' }}>{{ $school->title }}</option>
									@endforeach
								</select>

								@if ($errors->has('school'))
									<span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school') }}</strong>
                                    </span>
								@endif
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4 mt-4">
							<div class="col-md-6">
								<div class="control-group">
									<div class="control control-checkbox">
										<label>
											Принимаю <a href="http://showtime-fest.com/rules/" target="_blank"
											            class="btn-default-link">правила участия</a>
											<input type="checkbox" required id="accept_rules"/>
											<div class="control_indicator"></div>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group row justify-content-center mt-4 mt-5 pt-3">
							<div class="col-md-6">
								<button type="submit" class="btn btn-default btn-block invert" id="register_btn" disabled>
									Регистрация
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('head')
	<style>
		[v-cloak] {
			display: none;
		}
	</style>
@endpush
@push('script')
	<script>
        $( function () {
            var school = $( '#school' );
            school.select2( {
                placeholder : "Выберите студию / школу",
	            tags: true
            } ).on( 'select2:select', function () {
                Vue.school = school.val();
            } );
            $('form input').on('input', function(){
                var form = $('form');
                if(
                    !form.find('#email').val()
                    || !form.find('#name').val()
                    || !form.find('#surname').val()
                    || !form.find('#tel').val()
                    || !form.find('#country').val()
                    || !form.find('#city').val()
                    || !form.find('#crew').val()
                    || !form.find('#school').val()
                ){
                    var btn = form.find('#register_btn');
	                btn.attr('disabled', 'disabled');
                    if(!btn.hasClass('invert')){
                        btn.addClass('invert');
                    }
                } else {
                    form.find('#register_btn').attr('disabled', false).removeClass('invert');
                }
            });
        } );
	</script>
@endpush