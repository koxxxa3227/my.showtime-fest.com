@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<div class="row my-2">
			<div class="col text-center">
				<a href="{{ action('AdminController@masterClassCategories', ['type' => 'create']) }}"
				   class="btn btn-link">Типы танцев</a>
			</div>
			<div class="col text-center">
				{{--<a href="{{ action('AdminController@masterClassRequests') }}" class="btn btn-link">Записи на мастер-классы</a>--}}
			</div>
			<div class="col text-center">
				<a href="{{ action('AdminController@masterClass', ['type' => 'create']) }}"
				   class="btn btn-link">Создать новый масстер-класс</a>
			</div>
		</div>
		<h1 class="text-center default-color font-weight-bold mb-3">Мастер-классы</h1>
		<div class="table-responsive mt-4">
			<table class="table table-hover">
				<thead>
				<tr class="text-center">
					<th>#</th>
					<th>Телефон</th>
					<th>EMail</th>
					<th>К кому идёт</th>
					<th>Сумма</th>
					<th>Скидка</th>
					<th>Дата</th>
				</tr>
				</thead>
				<tbody>
				@foreach($requests as $item)
					<tr class="text-center">
						<td>{{ $item->id }}</td>
						<td>{{ $users[$item->user_id]->tel }}</td>
						<td>{{ $users[$item->user_id]->email }}</td>
						<td>{{ $masterClasses[$item->master_class_id]->name }}</td>
						<td>{{ $item->amount }}</td>
						<td>{{ $item->discount }}%</td>
						<td>{{ $masterClasses[$item->master_class_id]->date->format('d.m.Y') }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

	</div>
@endsection