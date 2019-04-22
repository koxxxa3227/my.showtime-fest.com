<nav class="navbar navbar-expand-md navbar-light my-navi">
	<div class="container-fluid">
		<!-- Left Side Of Navbar -->
		<ul class="navbar-nav mr-auto">
			@yield('left-navbar-menu')
		</ul>

		<!-- Right Side Of Navbar -->
		<ul class="navbar-nav ml-auto d-none d-md-flex">
			<!-- Authentication Links -->
			@guest
				<li class="nav-item">
					<a class="nav-link btn btn-default mr-4" href="{{ route('login') }}">Войти</a>
				</li>
				<li class="nav-item">
					@if (Route::has('register'))
						<a class="nav-link btn btn-default" href="{{ route('register') }}">Регистрация</a>
					@endif
				</li>
			@else
				<li class="nav-item">
					<a href="{{ action('ProfileController@myMasterClasses') }}" class="btn btn-default mr-4">Мастер-классы</a>
				</li>
				<li class="nav-item">
					<a href="{{ action('ProfileController@tickets') }}" class="btn btn-default mr-4">Билеты</a>
				</li>
				<li class="nav-item">
					<a href="{{ action('ProfileController@applications') }}" class="btn btn-default success mr-4">Заявки</a>
				</li>
				<li class="nav-item">
					<a href="{{ action('ProfileController@index') }}" class="btn btn-default mr-4">Профиль</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('logout') }}" class="btn btn-default invert">Выход</a>
				</li>
			@endguest
		</ul>
		<div class="d-block w-100 d-md-none">
			@guest
				<a href="{{ route('login') }}" class="btn btn-default d-block py-2 fz-14"
				   style="height: auto;">Войти</a>
				@if(Route::has('register'))
					<a href="{{ route('register') }}" class="btn btn-default d-block mt-2 py-2 fz-14"
					   style="height: auto;">Регистрация</a>
				@endif
			@else
				<a href="{{ action('ProfileController@myMasterClasses') }}" class="btn btn-default d-block py-2 fz-14">Мастер-классы</a>
				<a href="{{ action('ProfileController@tickets') }}" class="btn btn-default d-block py-2 mt-2 fz-14">Билеты</a>

				<a href="{{ action('ProfileController@applications') }}" class="btn btn-default success mt-2 d-block py-2 fz-14"
				   style="height: auto;">Заявки</a>
				<a href="{{ action('ProfileController@index') }}" class="btn btn-default d-block mt-2 py-2 fz-14"
				   style="height: auto;">Профиль</a>
				<a href="{{ route('logout') }}" class="btn btn-default invert d-block mt-2 py-2 fz-14"
				   style="height: auto;">Выход</a>
			@endguest
		</div>
	</div>
</nav>
