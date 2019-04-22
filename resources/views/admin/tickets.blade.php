@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center default-color font-weight-bold my-3">Проданные билеты</h1>
		<div class="mt-4">
			<div class="table-responsive mt-4">
				<table class="table table-hover">
					<thead>
					<tr class="text-center">
						<th>#</th>
						<th>Email</th>
						<th>Кол-во</th>
						<th>Сумма</th>
						<th>Дата создания</th>
					</tr>
					</thead>
					<tbody>
					@foreach($tickets as $ticket)
						<tr class="text-center">
							<td>{{ $ticket->id }}</td>
							<td>{{ optional($ticket->user)->email }}</td>
							<td>{{ intval($ticket->amount / config('custom.ticket_price')) }}</td>
							<td>{{ $ticket->amount }} грн</td>
							<td>{{ date_formatter($ticket->created_at) }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection