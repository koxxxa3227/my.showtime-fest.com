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
				<a href="{{ action('AdminController@masterClassRequests') }}"
				   class="btn btn-link">Записи на мастер-классы</a>
			</div>
			<div class="col text-center">
				@if($type == 'edit')
					<a href="{{ action('AdminController@masterClass', ['type' => 'create']) }}"
					   class="btn btn-link">Создать новый масстер-класс</a>
				@endif
			</div>
		</div>
		<h1 class="text-center default-color font-weight-bold mb-3">Мастер-классы</h1>
		<div class="mt-4">
			<form action="{{ action('AdminController@postMasterClass', ['type' => $type, 'id' => $masterClass->id]) }}"
			      method="post" v-cloak enctype="multipart/form-data">
				@csrf
				<div class="form-group row">
					<div class="col-md-3">
						<input type="text" name="name" id="name" class="form-control" required
						       placeholder="Введите ФИО" value="{{ $masterClass->name }}">
					</div>
					<div class="col-md-3">
						<input type="date" name="date" id="date" class="form-control" required title="Дата"
						       placeholder="Выберите дату" value="{{ optional($masterClass->date)->format('Y-m-d') }}">
					</div>
					<div class="col-md-3">
						<select name="master_class_category_id" id="master_class_category_id" title="Тип танца"
						        class="form-control" required>
							<option value="">Выберите тип танца</option>
							@foreach($categories as $category)
								<option value="{{ $category->id }}"
										{{ $masterClass->master_class_category_id == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-3">
						<input type="text" name="level" id="level" class="form-control" required
						       value="{{ $masterClass->level }}" placeholder="Уровень класса">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-3">
						<input type="number" step=".01" name="price" id="price" class="form-control" required
						       placeholder="Введите цену" value="{{ $masterClass->price }}" min="1">
					</div>
					<div class="col-md-3">
						<input type="text" name="address" id="address" class="form-control" required
						       placeholder="Введите адресс" value="{{ $masterClass->address }}">
					</div>
					<div class="col-md-3">
						<input type="time" name="time" id="time" class="form-control" required
						       placeholder="Введите время"
						       value="{{ \Carbon\Carbon::parse($masterClass->time)->format('H:i') }}">
					</div>
					<div class="col-md-3">
						<select name="count" id="count" title="Ограничение по участникам"
						        class="form-control" required>
							<option value="">Выберите ограничение по участников</option>
							@for($i = 5; $i<= 50; $i+=5)
								<option value="{{ $i }}" {{ $i == $masterClass->count ? 'selected' : '' }}>{{ $i }}</option>
							@endfor
						</select>
					</div>
				</div>
				<div class="row justify-content-start">
					<div class="col-md-3">
						<div class="custom-file">
							<input type="file" name="image" id="image" class="custom-file-input" v-model="image"
							       accept="image/png, image/jpeg">
							<label for="image" class="custom-file-label">@{{ image | shortName }}</label>
						</div>
						@if($path = $masterClass->image)
							<img src="/uploads/{{ $path }}" alt="Header image" class="img-fluid mt-2" width="100%">
						@endif
					</div>
				</div>
				<div class="text-right mt-4">
					<button type="submit" class="btn btn-default">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
@endsection
@push('script')
	<script>
        new Vue( {
            el : '#app',
            data : {
                image : ''
            },
            filters : {
                shortName : function ( title ) {
                    return title.replace( /^.+[\\\/]([^\\\/]+)$/, '$1' );
                }
            }
        } );
	</script>
@endpush
@push('head')
	<style>
		[v-cloak] {
			display: none;
		}
	</style>
@endpush