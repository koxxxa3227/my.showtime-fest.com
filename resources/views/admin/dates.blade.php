@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center default-color font-weight-bold mt-3">Даты</h1>
		<form action="{{ action('AdminController@postDates', ['type' => $type, 'id' => $date->id]) }}"
		      method="post" class="mt-4">
			@csrf
			<div class="row form-group justify-content-center">
				<div class="col-md-4">
					<div class="input-group">
						<input type="text" name="title" id="title"
						       class="form-control"
						       placeholder="Название" value="{{ $date->title }}">
						<div class="input-group-append">
							<button type="submit" class="btn btn-default btn-block">Сохранить</button>
						</div>
					</div>
				</div>
			</div>
		</form>

		@if($type == 'create')
			<div class="table-responsive mt-3">
				<table class="table table-hover">
					<thead>
					<tr class="text-center">
						<th>Название</th>
						<th>Опции</th>
					</tr>
					</thead>
					<tbody>
					@foreach($dates as $item)
						<tr class="text-center">
							<td>{{ $item->title }}</td>
							<td>
								<a href="{{ action('AdminController@dates', ['type' => 'edit', 'id' => $item->id]) }}">
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