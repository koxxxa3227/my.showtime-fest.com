
<div class="row">
	<div class="col">
		<a class="btn btn-default btn-block py-1" href="{{ action('AdminController@index') }}">Участники</a>
	</div>
	<div class="col">
		<a class="btn btn-default btn-block py-1" href="{{ action('AdminController@dates', 'create') }}">Даты</a>
	</div>
	<div class="col">
		<a class="btn btn-default btn-block py-1" href="{{ action('AdminController@categories', 'create') }}">Категории</a>
	</div>
	<div class="col">
		<a class="btn btn-default btn-block py-1" href="{{ action('AdminController@schools', 'create') }}">Студии / Школы</a>
	</div>
	<div class="col">
		<a class="btn btn-default btn-block py-1" href="{{ action('AdminController@applications') }}">Заявки</a>
	</div>
	<div class="col">
		<a class="btn btn-default btn-block py-1" href="{{ action('AdminController@tickets') }}">Прод. билеты</a>
	</div>
	<div class="col">
		<a class="btn btn-default btn-block py-1" href="{{ action('AdminController@masterClasses') }}">Мастер-классы</a>
	</div>
</div>
@push('head')
    <style>
        .py-1{
	        padding-top: .25rem !important;
	        padding-bottom: .25rem !important;
        }
    </style>
@endpush