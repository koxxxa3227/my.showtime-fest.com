@extends('layouts.app')

@section('content')
	<div class="container">
		<h1 class="text-center text-uppercase default-color font-weight-bold">Создание нового пароля</h1>
		<form action="{{ action('ProfileController@postGetPassword') }}" method="post" class="mt-5">
			<input type="hidden" name="verify_code" value="{{ $verify_code }}">
			@csrf
			<div class="row justify-content-center">
				<div class="col-lg-4">
					<input type="password" name="password" id="password" class="form-control" placeholder="Введите новый пароль">
				</div>
			</div>
			<div class="mt-4 text-center">
				<button type="submit" class="btn btn-default mx-5">Сохранить</button>
			</div>
		</form>
	</div>
@endsection
