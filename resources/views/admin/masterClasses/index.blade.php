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
				<a href="{{ action('AdminController@masterClassRequests') }}" class="btn btn-link">Записи на мастер-классы</a>
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
					<th>ФИО</th>
					<th>Дата</th>
					<th>Тип танца</th>
					<th>Уровень класса</th>
					<th>Цена</th>
					<th>Адресс</th>
					<th>Время</th>
					<th>Кол-во участников</th>
					<th>*</th>
				</tr>
				</thead>
				<tbody>
				@foreach($masterClasses as $item)
					<tr class="text-center">
						<td>{{ $item->name }}</td>
						<td>{{ $item->date->format('d.m.Y') }}</td>
						<td>{{ $categories[$item->master_class_category_id]->title }}</td>
						<td>{{ $item->level }}</td>
						<td>{{ $item->price }}</td>
						<td>{{ $item->address }}</td>
						<td>{{ \Carbon\Carbon::parse($item->time)->format('H:i') }}</td>
						<td>{{ $item->count }}</td>
						<td>
							<a href="{{ action('AdminController@masterClass', ['type' => 'edit', 'id' => $item->id]) }}">
								<i class="fa fa-edit"></i>
							</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

	</div>
@endsection