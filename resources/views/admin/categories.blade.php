@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center default-color font-weight-bold mt-3">Категории</h1>
		<form action="{{ action('AdminController@postCategories', ['type' => $type, 'id' => $category->id]) }}"
		      method="post" class="mt-4">
			@csrf
			<div class="row form-group">
				<div class="col-md-4">
					<select name="date_id" id="date" class="form-control" title="Выберите дату">
						@foreach($dates as $date)
							<option value="{{ $date->id }}" {{ $category->date_id == $date->id ? 'selected' : '' }}>{{ $date->title }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-4">
					<input type="text" name="title" id="title"
					       class="form-control"
					       placeholder="Название" value="{{ $category->title }}">
				</div>
				<div class="col-md-4">
					<input type="number" min="1" step="0.01" name="price" id="price" class="form-control"
					       placeholder="Цена" value="{{ $category->price }}">
				</div>
			</div>
			<div class="row form-group">
				<div class="col ">
					<input type="number" min="1" step="1" name="participant_count" id="participant_count"
					       class="form-control"
					       placeholder="Количество участников" value="{{ $category->participant_count }}">
				</div>
				<div class="col ">
					<input type="number" min="1" step="1" name="min_participant_count" id="min_participant_count"
					       class="form-control"
					       placeholder="Минимальное количество участников" value="{{ $category->min_participant_count }}">
				</div>
				<div class="col ">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<input type="checkbox" aria-label="Без трека" name="without_track" value="1"
								       id="without_track" {{ $category->without_track ? 'checked' : '' }}>
							</div>
						</div>
						<label for="without_track" class="form-control">Без трека</label>
					</div>
				</div>
				<div class="col ">
					<input type="text" min="1" step="0.01" name="length" id="length"
					       class="form-control"
					       placeholder="Длина трека" value="{{ $category->length }}">
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-default">Сохранить</button>
			</div>
		</form>

		@if($type == 'create')
			<div class="table-responsive mt-3">
				<table class="table table-hover">
					<thead>
					<tr class="text-center">
						<th>Дата</th>
						<th>Название</th>
						<th>Цена/уч</th>
						<th>Кол-во уч</th>
						<th>Мин кол-во уч</th>
						<th data-toggle="tooltip" title="Если активно 'Без трека' - отмечено галочкой">Без трека</th>
						<th>Длина трека</th>
						<th>Опции</th>
					</tr>
					</thead>
					<tbody>
					@foreach($categories as $item)
						<tr class="text-center">
							<td>{{ $item->date->title }}</td>
							<td>{{ $item->title }}</td>
							<td>{{ $item->price }}</td>
							<td>{{ $item->participant_count }}</td>
							<td>{{ $item->min_participant_count }}</td>
							<td>
								@if($item->without_track)
									<i class="fa fa-check text-success"></i>
								@else
									<i class="fa fa-times text-danger"></i>
								@endif
							</td>
							<td>{{ $item->length ? secToMin($item->length) : 'Не задано' }}</td>
							<td>
								<a href="{{ action('AdminController@categories', ['type' => 'edit', 'id' => $item->id]) }}">
									<i class="fa fa-edit"></i>
								</a>
								<a href="{{ action('AdminController@removeCategory', $item->id) }}">
									<i class="fa fa-trash text-danger"></i>
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
@push('script')
	<script>
        $( function () {
            $( '[data-toggle="tooltip"]' ).tooltip();
        } );
	</script>
@endpush