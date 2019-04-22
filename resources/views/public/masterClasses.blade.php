@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-10">
				<h1 class="text-center text-uppercase default-color font-weight-bold">Мастер-классы</h1>
				@foreach($dates as  $date)
					<h2 class="default-text-style fz-22 font-weight-bold text-left border-bottom w-50 mt-6 pb-2">{{ $date->format("d M Y") }}</h2>
					@foreach($masterClasses as $i => $masterClass)
						@if($date == $masterClass->date)
							<div class="masterClass mt-5">
								<div class="row">
									<div class="col-md-5">
										<img src="/uploads/{{ $masterClass->image }}"
										     alt="{{ $masterClass->category->title }}"
										     class="img-fluid rounded">
									</div>
									<div class="col-md-7">
										<div class="row">
											<div class="col-8 mk_name_cnt">
												<h3 class="default-text-style font-weight-bold fz-22">
													{{ $masterClass->name }}
												</h3>
												<h5 class="default-text-style font-weight-normal mb-0">
													{{ $masterClass->category->title }}
												</h5>
												<h5 class="default-text-style default-color secondary font-weight-normal">
													{{ $masterClass->level }}
												</h5>
												<h5 class="default-text-style font-weight-bold mt-3">
													{{ $masterClass->price }} грн
												</h5>
												<div class="mt-5 default-color secondary">
													<span class="descr_class">
														<i class="fas fa-map-marker-alt"></i>
														Место проведения: {{ $masterClass->address }}
													</span>
													<span class="descr_class">
														<i class="fas fa-clock"></i>
														Время: {{ \Carbon\Carbon::parse($masterClass->time)->format("H:i") }}
													</span>
													<span class="descr_class">
														<i class="fas fa-user"></i>
														Максимальное количество участников: {{ $masterClass->count }}
													</span>
												</div>
											</div>
											<div class="col-4 mk_go_tick">
												<div class="control-group"
												     @click.prevent="addToCart('{{ $masterClass->id }}')"
												     id="add_span_{{ $masterClass->id }}">
													<div class="control control-checkbox">
														<label class="default-text-style font-weight-bold">
															<span>Я иду!</span>
															<input type="checkbox" required/>
															<div class="control_indicator"></div>
														</label>
													</div>
												</div>
												<div class="control-group active"
												     @click.prevent="removeFromCart('{{ $masterClass->id }}')"
												     id="remove_span_{{ $masterClass->id }}"
												     style="display: none;">
													<div class="control control-checkbox">
														<label class="default-text-style font-weight-bold">
															<span>Я иду!</span>
															<input type="checkbox"
															       value="{{ $masterClass->id }}"
															       required/>
															<div class="control_indicator"></div>
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						@endif
					@endforeach
				@endforeach
				<div class="row justify-content-center mt-5 pt-5" v-if="cart.length > 0" v-cloak>
					<div class="col-md-5  border-top fz-16 pt-4">
						<h5>Вы записались на:</h5>
						<ul class="list-unstyled">
							<li v-for="(cart, index) in cart">@{{ index+1 }}. @{{ cart.name }}</li>
						</ul>
						<h5 class="mt-4">
							Сумма: @{{ amount | toFixed2 }} грн
							<span v-if="cart.length == 2 || cart.length == 3">
								(Скидка: @{{  amount_in_cart | toFixed2 }} грн)
							</span>
							<span v-else-if="cart.length >= 4">
								(Скидка: @{{ amount_in_cart | toFixed2 }} грн)
							</span>
						</h5>
						<div class="mt-5">
							При записи на несколько мастер-классов одновременно мы сделаем скидку:
							<ul class="list-unstyled mt-4">
								<li class="default-text-style font-weight-bold">2 класса - @{{ percents[2] }}%</li>
								<li class="default-text-style font-weight-bold">3 класса - @{{ percents[3] }}%</li>
								<li class="default-text-style font-weight-bold">4 и более классов - @{{ percents[4] }}%</li>
							</ul>
						</div>
						<div class="text-center mt-5">
							<form action="{{ action('ProfileController@masterClasses') }}" method="post">
								@csrf
								<input type="text" name="mc_ids" v-model="done_cart_ids" hidden>
								<button type="submit" class="btn btn-default btn-block">Записаться и оплатить</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('head')
	<style>
		[v-cloak] {
			display: none;
		}
	</style>
@endpush
@push('script')
	<script>
        new Vue( {
            el : '#app',
            data : {
                masterClasses : {!! json_encode($masterClasses) !!},
                cart : [],
                amount_in_cart : 0,
                done_cart_ids : [],
                amount : 0,
                percents : {!! json_encode(config('custom.master_class_percents')) !!}
            },
            methods : {
                addToCart : function ( id ) {
                    var masterClass = false, cart = this.cart, amount_in_cart = this.amount_in_cart;
                    this.masterClasses.forEach( function ( mc ) {
                        if ( id == mc.id ) {
                            masterClass = mc;
                            return false;
                        }
                    } );
                    cart.push( masterClass );
                    this.done_cart_ids.push( masterClass.id );
                    var amount = 0, percent = 0;
                    cart.forEach( function ( cart, index ) {
                        amount += cart.price * 1;
                    } );
                    var result = amount;
                    if ( cart.length == 2 || cart.length == 3 ) {
                        percent = this.percents[ cart.length ];
                    } else if ( cart.length >= 4 ) {
                        percent = this.percents[ 4 ];
                    }
                    amount = amount - ( amount * percent / 100 );
                    this.amount = amount;
                    $( '#add_span_' + id ).hide();
                    $( '#remove_span_' + id ).show();

                    console.log(result, amount);
                    amount_in_cart = result - amount;
                    this.amount_in_cart = amount_in_cart;
                },
                removeFromCart : function ( id ) {
                    var _this = this, amount = 0, amount_in_cart = this.amount_in_cart;
                    this.cart.forEach( function ( cart, index ) {
                        if ( cart.id == id ) {
                            _this.cart.splice( index, 1 );
                            _this.done_cart_ids.splice( index, 1 );
                            return;
                        }
                    } );
                    this.cart.forEach(function ( cart ) {
                        amount += cart.price * 1;
                    });
                    var percent = 0, cart = this.cart;
                    if ( cart.length == 2 || cart.length == 3 ) {
                        percent = this.percents[ cart.length ];
                    } else if ( cart.length >= 4 ) {
                        percent = this.percents[ 4 ];
                    }
                    var result = amount;
                    amount = amount - ( amount * percent / 100 );
                    this.amount = amount;
                    console.log( this.done_cart_ids );

                    $( '#add_span_' + id ).show();
                    $( '#remove_span_' + id ).hide();

                    amount_in_cart = result - amount;
	                this.amount_in_cart = amount_in_cart;
                }
            },
	        filters: {
                toFixed2: function(value){
                    return value.toFixed(2);
                }
	        }
        } );
	</script>
@endpush
