@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center default-color font-weight-bold my-3">Школы / Студии</h1>
		<div class="mt-4">
			<form action="{{ action('AdminController@postSchools', ['type' => $type, 'id' => $school->id]) }}"
			      method="post">
				@csrf
				<div class="row justify-content-center">
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" name="title" id="title" class="form-control"
							       placeholder="Введите название" value="{{ $school->title }}">
							<div class="input-group-append">
								<button type="submit" class="btn btn-default btn-block">
									Сохранить
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>

			@if($type == 'create')
				<div class="table-responsive mt-4">
					<table class="table table-hover">
						<thead>
						<tr class="text-center">
							<th>Название</th>
							<th>Кол-во заявок студии</th>
							<th>Кол-во пользователей студии</th>
							<th>Опции</th>
						</tr>
						</thead>
						<tbody>
						@foreach($schools as $item)
							<tr class="text-center">
								<td>{{ $item->title }}</td>
								<td>{{ $appsCount[$item->id] }}</td>
								<td>{{ $usersCount[$item->id] }}</td>
								<td>
									<a href="{{ action('AdminController@schools', ['type' => 'edit', 'id' => $item->id]) }}">
										<i class="fa fa-edit"></i>
									</a>
									{{--@if($canRemove[$item->id])--}}
									<a href="{{ action('AdminController@removeSchool', $item->id) }}">
										<i class="fa fa-trash text-danger"></i>
									</a>
									{{--@endif--}}
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			@endif
		</div>
	</div>
@endsection