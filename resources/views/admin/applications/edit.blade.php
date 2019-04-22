@extends('layouts.app')

@section('content')

	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center default-color font-weight-bold mt-3">Заявка #{{ $apl->id }} (Категория: {{ $apl->category->title }})</h1>
		<form action="{{ action('AdminController@postEditApplication', $apl->id) }}" method="post">
			@csrf
			<div class="row form-group">
				<div class="col-md-6">
					<div class="box">
						<h5 class="default-text-style default-color">Создатель заявки</h5>
						<input type="text" name="user" id="user" class="form-control"
						       value="{{ $apl->user->name . ' ' . $apl->user->surname }}" readonly
						       title="Создатель заявки">
					</div>
				</div>
				<div class="col-md-6">
					<div class="box">
						<h5 class="default-text-style default-color">Участник(и)</h5>
						@foreach($pts as $i => $participant)
							<div class="input-group">
								<label class="input-group-prepend mb-0 default-text-style py-3 px-2"
								       for="participant_{{ $participant->id }}">
									Участник #{{ $i+1 }}
								</label>
								<input type="text" name="participant" id="participant_{{ $participant->id }}"
								       class="form-control" value="{{ $participant->name }}" title="Участник #{{ $i }}"
								       readonly>
							</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<div class="box">
						<h5 class="default-text-style default-color">Трек</h5>
						<div class="w-100"></div>
						@if($apl->track)
							<a href="/uploads/{{ $apl->track->path }}">Открыть</a>
						@else
							Трек не загружен
						@endif
					</div>
				</div>
				<div class="col-md-6">
					<div class="box">
						<h5 class="default-text-style default-color">Документ(ы)</h5>
						@foreach($pts as $i => $participant)
							<ul class="list-inline">
								<li class="list-inline-item default-text-style">
									Участник #{{ $i + 1 }}
								</li>
								@if($document = $participant->document)
									<li class="list-inline-item ml-4">
										<a href="/uploads/{{ $document->path }}">
											Открыть
										</a>
									</li>
									<li class="list-inline-item ml-3">
										<select name="accepted_docs[]" id="accepted" class="form-control" title="Статус" data-toggle="tooltip">
											<option value="{{ false }}" {{ $document->accepted == 0 ? 'selected' : '' }}>Возраст не подтвержден</option>
											<option value="{{ true }}" {{ $document->accepted == 1 ? 'selected' : '' }}>Возраст подтвержден</option>
										</select>
									</li>
								@else
									<li class="list-inline-item">Документ не загружен</li>
								@endif
							</ul>
						@endforeach

					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<div class="box">
						<h5 class="default-text-style default-color">Статус оплаты</h5>
						<select name="is_paid" id="is_paid" class="form-control" title="Статус оплаты">
							<option value="0" {{ $apl->is_paid == false ? 'selected' : '' }}>Не оплачена</option>
							<option value="1" {{ $apl->is_paid == true ? 'selected' : '' }}>Оплачена</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="box">
						<h5 class="default-text-style default-color">Статус заявки</h5>
						<select name="accepted" id="accepted" class="form-control" title="Статус заявки">
							<option value="0" {{ $apl->accepted == false ? 'selected' : '' }}>Не принята</option>
							<option value="1" {{ $apl->accepted == true ? 'selected' : '' }}>Принята</option>
						</select>
					</div>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-default">Сохранить</button>
			</div>
		</form>
	</div>
@endsection
@push('head')
	<style>
		.box {
			border: 1px solid #c7c7c7;
			padding: 1rem;
			height: 100%;
		}
	</style>
@endpush
@push('script')
	<script>
		$(function(){
		    $('[data-toggle="tooltip"]').tooltip();
		});
	</script>
@endpush