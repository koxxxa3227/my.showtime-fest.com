@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<div class="row my-2">
			<div class="col text-center">
				@if($type == 'edit')
					<a href="{{ action('AdminController@masterClassCategories', ['type' => 'create']) }}"
					   class="btn btn-link">Типы танцев</a>
				@endif
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
		<form action="{{ action('AdminController@postMasterClassCategories', ['type' => $type, 'id' => $category->id]) }}" method="post">
			@csrf
			<div class="row justify-content-center">
				<div class="col-md-4">
					<div class="input-group">
						<input type="text" class="form-control" id="title" name="title" value="{{ $category->title }}"
						       placeholder="Введите название">
						<div class="input-group-append">
							<button type="submit" class="btn btn-default ">Сохранить</button>
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
						<th>*</th>
					</tr>
					</thead>
					<tbody>
					@foreach($categories as $item)
						<tr class="text-center">
							<td>{{ $item->title }}</td>
							<td>
								<a href="{{ action('AdminController@masterClassCategories', ['type' => 'edit', 'id' => $item->id]) }}">
									<i class="fa fa-edit"></i>
								</a>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		@endif
	</div>
@endsection