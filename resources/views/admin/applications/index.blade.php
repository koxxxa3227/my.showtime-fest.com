@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<div class="row text-center my-4" id="data-info">
			<div class="col">
				<span class="font-weight-bold">Всего заявок</span>: {{ $_data['apps_count'] }}
			</div>
			<div class="col">
				<span class="font-weight-bold">Оплачено заявок</span>: {{ $_data['apps_paid_count'] }}/{{ $_data['apps_count'] }}
			</div>
			<div class="col">
				<span class="font-weight-bold">Подтверждено заявок</span>: {{ $_data['apps_accepted_count'] }}/{{ $_data['apps_count'] }}
			</div>
			<div class="col">
				<span class="font-weight-bold">Заявок с треками</span>: {{ $_data['apps_with_tracks'] }}/{{ $_data['apps_count'] }}
			</div>
			<div class="col">
				<span class="font-weight-bold">Заявок с документами</span>: {{ $_data['apps_with_docs'] }}/{{ $_data['apps_count'] }}
			</div>
		</div>
		<div class="text-right my-4">
			<a href="{{ action('AdminController@getApplicationsInExcel') }}" class="btn btn-default">
				Выгрузить в EXCEL
			</a>
		</div>
		<h1 class="text-center default-color font-weight-bold mt-3">Заявки</h1>
		<div class="table-responsive" v-cloak>
			<table class="table table-hover">
				<thead>
				<tr class="text-center">
					<td>#</td>
					<td>Участник</td>
					<td style="max-width: 150px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">Почта</td>
					<td>Дата</td>
					<td>Категория</td>
					<td style="max-width: 120px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">Город</td>
					<td style="max-width: 150px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">Студия / школа</td>
					<td>Трек</td>
					<td>Статус</td>
					<td>Оплачен</td>
					<td>Сумма заявки</td>
					<td>Кол-во док-тов</td>
					<td>Статус док-тов</td>
					<td>Дата создания</td>
					<td>
						Опции
					</td>
				</tr>
				</thead>
				<tbody>
				<tr class="text-center" v-for="(apl, index) in sortedApplications">
					<td>@{{ apl.id }}</td>
					<td>@{{ apl.user.name }} @{{ apl.user.surname }}</td>
					<td style="max-width: 150px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">@{{ apl.user.email }}</td>
					<td>@{{ apl.date.title }}</td>
					<td>@{{ apl.category.title }}</td>
					<td style="max-width: 120px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">@{{ apl.city }}</td>
					<td style="max-width: 150px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">@{{ apl.school.title }}</td>
					<td>
						<a :href="'/uploads/' + apl.track.path" v-if="apl.track">
							Открыть
						</a>
						<span v-else>
						<i class="fa fa-times text-danger"></i>
					</span>
					</td>
					<td>
						<i class="fa fa-check text-success" v-if="apl.accepted"></i>
						<i class="fa fa-sync text-secondary" v-else></i>
					</td>
					<td>
						<i class="fa fa-check text-success" v-if="apl.is_paid"></i>
						<i class="fa fa-times text-danger" v-else></i>
					</td>
					<td>
						@{{ apl.category.price * participants_count[apl.id] }} грн
					</td>
					<td>@{{ documents[apl.id] }}</td>
					<td>
						<span v-for="doc in apl.documents" class="ml-1">
							<i class="fa fa-check text-success" v-if="doc.accepted == 1"></i>
							<i class="fa fa-times text-danger" v-else></i>
						</span>
					</td>
					<td>@{{ apl.created_at | dateFormatter }}</td>
					<td>
						<a :href="'/admin/application/' + apl.id + '/edit'" target="_blank">
							<i class="fa fa-external-link-alt"></i>
						</a>
						<a :href="'/admin/application/' + apl.id + '/remove'">
							<i class="fa fa-trash text-danger"></i>
						</a>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script>
        moment.locale( 'ru' );
        new Vue( {
            el : '#app',
            data : {
                applications : {!! $applications !!},
                documents : {!! $documents !!},
                status : {
                    '0' : 'Не принята',
                    '1' : 'Принята'
                },
	            participants_count: {!! $participants_count !!}
            },
            computed : {
                sortedApplications() {
                    return this.applications.filter( apl => {
                        return apl;
                    } )
                }
            },
            filters : {
                dateFormatter( value ) {
                    return moment( value ).format( 'L' ) + ' ' + moment( value ).format( 'LT' );
                }
            }
        } );
	</script>
@endsection
@push('head')
    <style>
        #data-info span{
	        color: #66b5f8;
        }
	    [v-cloak]{
		    display: none;
	    }
    </style>
@endpush
