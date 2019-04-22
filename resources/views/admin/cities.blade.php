@extends('layouts.app')
@section('content')
	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center default-color font-weight-bold mt-3">Города</h1>

		<form action="{{ action('AdminController@postCities', $type, $city->id) }}" method="post" class="mt-4">
			@csrf
			<div class="row">
				<div class="col-md-6">
					<select name="country_id" id="country_id" class="form-control" title="Выберите город">
						<option value="1" {{ $city->country_id == 1 ? 'selected' : '' }}>Украина</option>
					</select>
				</div>
				<div class="col-md-6">
					<input type="text" name="title" id="city" class="form-control" placeholder="Введите название города"
					       value="{{ $city->title }}">
				</div>
			</div>
			<div class="text-right mt-4">
				<button type="submit" class="btn btn-default">Сохранить</button>
			</div>
		</form>

		@if($type == 'create')
			<div class="row justify-content-end mt-4">
				<div class="col-md-4">
					<select name="country" id="country" title="Страна" class="form-control"
					        v-model="selected_country_id">
						<option value="">Выберите город</option>
						<option value="1">Украина</option>
					</select>
				</div>
				<div class="col-md-4">
					<input type="text" name="city" id="city" class="form-control" placeholder="Город" v-model="search">
				</div>
			</div>
			<div class="table-responsive position-relative" style="min-height:400px">
				<div class="preloader" v-show="loading">
					<img src="/uploads/2.gif" alt="Loading" class="img-fluid">
				</div>
				<table class="table table-hover">
					<thead>
					<tr class="text-center">
						<th>Страна</th>
						<th>Город</th>
						<th>Опции</th>
					</tr>
					</thead>
					<tbody>
					<tr class="text-center" v-for="(city, index) in sortedCities">
						<td>@{{ city.country.title }}</td>
						<td>@{{ city.title }}</td>
						<td>
							<a :href="'/admin/cities/edit/' + city.id"><i class="fa fa-edit"></i></a>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		@endif
	</div>
	<script>
        new Vue( {
            el : '#app',
            data : {
                cities : {!! $cities !!},
                selected_country_id : '',
                search : '',
	            loading: true,
            },
	        mounted(){
                this.loading = false;
	        },
            computed : {
                sortedCitiesByCountry() {
                    return this.cities.filter( city => {
                        if ( this.selected_country_id != '' ) {
                            if ( this.selected_country_id == city.country_id )
                                return city;
                        } else {
                            return city;
                        }
                    } );
                },
                sortedCities() {
                    return this.sortedCitiesByCountry.filter( city => {
                        return city.title.toLowerCase().indexOf( this.search.toLowerCase() ) > -1;
                    } );
                }
            }
        } );
	</script>
@endsection