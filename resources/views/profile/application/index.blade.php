@extends('layouts.app')
@section('content')
	<div class="container">
		<h1 class="text-center text-uppercase default-color font-weight-bold ">Заявки</h1>

		<div class="row justify-content-center mt-5 pt-3 mb-5">
			<div class="col-md-4 text-center">
				@if($applications->count() == 0)
					<p class="text-center default-text-style">У Вас нет заявок :(</p>
				@endif
				<a href="{{ action('ProfileController@application', ['type' => 'create']) }}"
				   class="btn btn-default danger mt-2 fz-17">Создать заявку</a>
			</div>
		</div>

		@foreach($applications as $application)
			<div class="row justify-content-center mt-4">
				<div class="col-md-8 box">
					<ul class="list-inline">
						<li class="list-inline-item">
							<h4 class="font-weight-bold default-text-style fz-22">Заявка #{{ $application->id }}</h4>
						</li>
						<li class="list-inline-item position-relative ml-3">
							@if($application->accepted)
								<span class="bg-accept-circle rounded-circle"><i class="fa fa-check"></i></span>
								<span class="default-text-style" style="margin-left: 30px;">Принята</span>
							@else
								<span class="bg-not-accept-circle rounded-circle"><i class="fa fa-times"></i></span>
								<span class="default-text-style" style="margin-left: 30px;">Еще не принята</span>
							@endif
						</li>
					</ul>

					<div class="row mt-5 justify-content-between">
						<div class="col-md-7">
							<h5 class="default-text-style font-weight-bold">Категория</h5>
							{{ $application->category->title }}
							<h5 class="default-text-style font-weight-bold mt-3">Студия</h5>
							{{ $application->school->title }}
						</div>
						<div class="col-md-3 default-text-style request_sum">
							<h5 class=" font-weight-bold mb-3">Сумма</h5>
							{{ $application->category->price * $application->participants->count() }} грн
							<div class="position-relative mt-3">
								@if($application->is_paid)
									<span class="bg-accept-circle rounded-circle" style="padding-top: 0px;"><i
												class="fa fa-check" style="margin-left: 2px;"></i></span>
									<span class="default-text-style" style="margin-left: 25px">Оплачена</span>
								@else
									<span class="bg-not-accept-circle rounded-circle" style="padding-top: 0px;"><i
												class="fa fa-times" style="margin-left: 1px;"></i></span>
									<span class="default-text-style" style="margin-left: 25px">Не оплачена</span>
									<a href="{{ action('LiqpayController@pay', $application->id) }}"
									   class="btn btn-default mt-3" style="padding: 3px 20px; height: 36px;">Оплатить</a>
								@endif
							</div>
						</div>
					</div>
					<h5 class="default-text-style font-weight-bold mt-5">Участник(и)</h5>
					@foreach($application->participants as $i => $participant)
						<div class="row mb-2 participant_row">
							<div class="col-md-4">
								#{{ $i+1 }} {{ $participant->name }}
							</div>
							<div class="col position-relative">
								@if($document = $participant->document)
									@if($document->accepted)
										<span class="bg-accept-circle rounded-circle" style="top: 0;"><i
													class="fa fa-check"></i></span>
										<span class="default-text-style"
										      style="margin-left: 25px">Возраст подтверждён</span>
									@else
										<span class="bg-not-accept-circle rounded-circle" style="top: 0;"><i
													class="fa fa-times"></i></span>
										<span class="default-text-style"
										      style="margin-left: 25px">Возраст не подтверждён</span>
									@endif
								@else
									<span class="bg-not-accept-circle rounded-circle" style="top: 0;"><i
												class="fa fa-times"></i></span>
									<span class="default-text-style"
									      style="margin-left: 25px">Возраст не подтверждён</span>
								@endif
							</div>
						</div>
					@endforeach
					<div class="mt-5 position-relative">
						<span class="default-text-style font-weight-bold mr-3">Трек</span>
						@if($application->track)
							<span class="bg-accept-circle rounded-circle" style="top: 0; left: 50px;"><i
										class="fa fa-check"></i></span>
							<span class="default-text-style"
							      style="margin-left: 25px">Загружен</span>
						@else
							<span class="bg-not-accept-circle rounded-circle" style="top: 0; left: 50px;"><i
										class="fa fa-times"></i></span>
							<span class="default-text-style"
							      style="margin-left: 25px">Не загружен</span>
						@endif
					</div>

					<div class="default-color secondary default-text-style mt-2 pt-4 fz-14">
						<p class="mb-2">
							Дата создания: {{ $application->created_at->format('d.m.Y H:i') }}
						</p>
						<p class="mb-0">
							Дата редактирования: {{ $application->updated_at->format('d.m.Y H:i') }}
						</p>
					</div>
					<div class="text-center mt-5 pt-3">
						<a href="{{ action('ProfileController@application', ['type' => 'edit', 'id' => $application->id]) }}"
						   class="btn btn-default px-5 font-weight-bold">
							Редактировать заявку
						</a>
					</div>
				</div>
			</div>
		@endforeach
	</div>
@endsection
