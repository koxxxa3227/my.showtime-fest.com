@extends('layouts.app')
@section("content")
	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center text-uppercase default-color font-weight-bold my-4">
			{{ $user->name }} {{ $user->surname }}: редактирование
		</h1>
		<form action="{{ action('AdminController@postEditUser', $user->id) }}" method="post">
			@csrf
			<div class="row form-group">
				<div class="col-md-4">
					<label for="name">Имя</label>
					<input type="text" class="form-control" id="name" value="{{ $user->name }}" name="name">
				</div>
				<div class="col-md-4">
					<label for="surname">Фамилия</label>
					<input type="text" class="form-control" id="surname" value="{{ $user->surname }}" name="surname">
				</div>
				<div class="col-md-4">
					<label for="email">Email</label>
					<input type="text" class="form-control" id="email" value="{{ $user->email }}" name="email">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
					<label for="tel">Телефон</label>
					<input type="tel" class="form-control" id="tel" value="{{ $user->tel }}" name="tel">
				</div>
				<div class="col-md-4">
					<label for="country">Страна</label>
					<input type="text" class="form-control" id="country" value="{{ $user->country }}" name="country">
				</div>
				<div class="col-md-4">
					<label for="city">Город</label>
					<input type="text" class="form-control" id="city" value="{{ $user->city }}" name="city">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
					<label for="crew">Crew</label>
					<input type="text" class="form-control" id="crew" value="{{ $user->crew }}" name="crew">
				</div>
				<div class="col-md-4">
					<label for="school">Студия</label>
					<select name="school" id="school" class="form-control">
						<option value="">Выберите школу / студию</option>
						@foreach($schools as $school)
							<option value="{{ $school->title }}" {{ optional($user->school)->title == $school->title ? 'selected' : '' }}>{{ $school->title }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-4">
					<label for="role_id">Роль</label>
					<select name="role_id" id="role_id" class="form-control">
						<option value="{{ \App\Models\User::ADMIN_ID }}" {{ \App\Models\User::ADMIN_ID == $user->role_id ? 'selected' : '' }}>
							Администратор
						</option>
						<option value="{{ \App\Models\User::USER_ID }}" {{ \App\Models\User::USER_ID == $user->role_id ? 'selected' : '' }}>
							Участник
						</option>
					</select>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-default">Сохранить</button>
			</div>
		</form>
	</div>
@endsection
@push('script')
	<script>
        $( '#school' ).select2( {
            placeholder : 'Выберите школу / студию'
        } );
	</script>
@endpush