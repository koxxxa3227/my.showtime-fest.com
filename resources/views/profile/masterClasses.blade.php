@extends('layouts.app')
@section('content')
	<div class="container">
		<h1 class="text-center text-uppercase default-color font-weight-bold">Мои Мастер-классы</h1>
		<div class="row justify-content-center mt-5 pt-3 mb-5">
			<div class="col-md-4 text-center">
				@if($umcs->count() == 0)
					<p class="text-center default-text-style">Вы пока никуда не идёте :(</p>
				@endif
				<a href="{{ action('ProfileController@masterClasses') }}"
				   class="btn btn-default danger mt-2 fz-17">Записаться на мастер-класс</a>
			</div>
		</div>
		@foreach($umcs as $umc)
			@if($mc = $masterClasses[$umc->master_class_id])
				<div class="row justify-content-center mt-4">
					<div class="col-md-8 box py-4">
						<ul class="list-inline mb-5">
							<li class="list-inline-item">
								<h4 class="font-weight-bold default-text-style fz-22">Заявка #{{ $umc->id }}</h4>
							</li>
							<li class="list-inline-item position-relative ml-3">
								@if($umc->is_paid)
									<span class="bg-accept-circle rounded-circle"><i class="fa fa-check"></i></span>
									<span class="default-text-style" style="margin-left: 30px;">Всё ок</span>
								@endif
							</li>
						</ul>
						<div class="row">
							<div class="col-md-4">
								<img src="/uploads/{{ $mc->image }}" class="img-fluid rounded">
							</div>
							<div class="col-md-8 mk_name_cnt">
								<h3 class="default-text-style font-weight-bold fz-22">
									{{ $mc->name }}
								</h3>
								<h5 class="default-text-style font-weight-normal mb-0">
									{{ $mc->category->title }}
								</h5>
								<h5 class="default-text-style default-color secondary font-weight-normal">
									{{ $mc->level }}
								</h5>
							</div>
						</div>
						<div class="row mt-5">
							<div class="col-md-10 default-color secondary mk_plate_desrc">
								<h5>
									<i class="fas fa-map-marker-alt"></i>
									Место проведения: {{ $mc->address }}
								</h5>
								<h5>
									<i class="fas fa-clock"></i>
									Время: {{ \Carbon\Carbon::parse($mc->time)->format("H:i") }}
								</h5>
							</div>
							<div class="col-md-2 text">
								<h5 class="font-weight-bold">Сумма</h5>
								{{ $mc->price }}
								<div class="position-relative mt-3">
									@if($umc->is_paid)
										<span class="bg-accept-circle rounded-circle"><i
													class="fa fa-check" style="margin-left: 2px;"></i></span>
										<span class="default-text-style" style="margin-left: 25px">Оплачена</span>
									@else
										<span class="bg-not-accept-circle rounded-circle" style="padding-top: 0px;"><i
													class="fa fa-times" style="margin-left: 1px;"></i></span>
										<span class="default-text-style" style="margin-left: 25px">Не оплачена</span>
									@endif
								</div>
							</div>
						</div>
						<div class="mt-4 default-color secondary">
							Дата создания: {{ $umc->created_at->format('d.m.Y H:i') }}
						</div>
					</div>
				</div>
			@endif
		@endforeach
	</div>
@endsection
