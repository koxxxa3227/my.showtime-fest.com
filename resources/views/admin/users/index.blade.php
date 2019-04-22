@extends('layouts.app')
@section("content")
	<div class="container-fluid">
		@include('particles.admin.menu')
		<h1 class="text-center text-uppercase default-color font-weight-bold my-4">
			Пользователи
		</h1>
		<div v-cloak>
			<div class="row justify-content-end mb-4">
				<div class="col-md-4">
					<input type="text" name="search" id="search" class="form-control" v-model="search"
					       placeholder="Введите имя, фамилию или почту пользователя">
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
					<tr class="text-center">
						<th>Имя</th>
						<th>Фамилия</th>
						<th>Почта</th>
						<th>Телефон</th>
						<th>Город</th>
						<th>Crew</th>
						<th>Студия / Школа</th>
						<th>*</th>
					</tr>
					</thead>
					<tbody>
					<tr class="text-center" v-for="(user, index) in this.sortedUsers">
						<td>
							<a :href="'/admin/attempt-login/user/' + user.id">
								@{{ user.name }}
							</a>
						</td>
						<td>@{{ user.surname }}</td>
						<td>@{{ user.email }}</td>
						<td>@{{ user.tel }}</td>
						<td>@{{ user.city }}</td>
						<td>@{{ user.crew }}</td>
						<td>@{{ user.school ? user.school.title : '' }}</td>
						<td>
							<a :href="'/admin/user/' + user.id + '/edit'">
								<i class="fa fa-edit"></i>
							</a>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
@push('script')
	<script>
        new Vue( {
            el : '#app',
            data : {
                users : {!! $users !!},
                search : ''
            },
            computed : {
                sortedUsers : function () {
                    var _this = this;
                    return this.users.filter( function ( user ) {
                        if (
                            user.name.toLowerCase().indexOf( _this.search.toLowerCase() ) > -1
                            || user.surname.toLowerCase().indexOf( _this.search.toLowerCase() ) > -1
                            || user.email.toLowerCase().indexOf( _this.search.toLowerCase() ) > -1
                        ) {
                            return user;
                        }
                    } )
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