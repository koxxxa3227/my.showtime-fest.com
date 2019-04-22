@extends('layouts.app')

@section('content')
	<div class="container">
		<h1 class="text-center text-uppercase default-color font-weight-bold">
			@if($type == 'create')
				Создание заявки
			@else
				Редактирование заявки #{{ $apl->id }}
			@endif
		</h1>
		<form action="{{ action('ProfileController@postApplication', ['id' => $apl->id, 'type' => 'create']) }}"
		      method="post" id="form"
		      enctype="multipart/form-data" class="mt-5" v-cloak>
			@csrf
			<input type="hidden" name="pay" v-model="pay">
			<div class="row form-group justify-content-center mt-4">
				<div class="col-lg-4">
					<select name="date_id" id="date_id" class="form-control" required title="Выберите день"
					        v-model="selected_date_id">
						<option value="">Выберите день</option>
						<option :value="date.id" v-for="date in dates">
							@{{ date.title }}
						</option>
					</select>
				</div>
			</div>
			<div class="row form-group justify-content-center mt-4">
				<div class="col-lg-4">
					<select name="category_id" id="category_id" class="form-control" required
					        :disabled="apl.is_paid > 0"
					        title="Выберите категорию" v-model="selected_category_id" @change="setCategory">
						<option value="">Выберите категорию</option>
						<option v-for="(category, index) in categories" :value="category.id"
						        v-if="category.date_id == selected_date_id">
							@{{ category.title }}
						</option>
					</select>
				</div>
			</div>

			<div class="row form-group justify-content-center mt-5">
				<div class="col-lg-4 default-color danger default-text-style">
					Для каждого учасника необходимо загрузить свидетельство о рождении для подтверждения возраста (файл макс 5 Мб)
				</div>
			</div>

			<div class="row form-group justify-content-center mt-5" v-for="(participant, i) in participants">
				<div class="col-lg-8 default-text-style offset-md-4">
					<div class="row">
						<div class="col-lg-6">
							<label :for="'participant_' + i">Участник #@{{ i + 1 }}</label>

							<input type="text" :name="'participants[' + i + '][name]'" :id="'participant_' + i"
							       class="form-control" placeholder="Введите имя"
							       v-model="participants[i].name" required>
						</div>
					</div>
					<div class="row btns_cnt">
						<button type="button" class="btn btn-default violet mt-4 px-4"
						        @click="removeParticipant(i)"
						        :disabled="(participants.length <= 1) || apl.is_paid > 0">
							Удалить участника
						</button>


						<div class="mt-4 ml-3 doc_btn_cnt">
							<label :for="'document_' + i" class="btn btn-default"
							       v-if="!participants[i].document">
								Загрузить документ
							</label>
							<div :for="'document_' + i" class="btn btn-default danger px-4"
							     @click="removeDoc(i)" v-else>
								Удалить документ
							</div>

							<input class="hidden document" :data-i="i" type="file" name="documents[]"
							       :id="'document_' + i"
							       accept=".png, .jpg, .jpeg">
							<span class="d-inline-block mt-2 doc_name">
									@{{ participants[i].document ? shortName(participants[i].document.path) : '' }}
								</span>
						</div>
					</div>
				</div>
			</div>
			<div class="row form-group justify-content-center mt-5 pt-3"
			     v-if="(choosedCategory && (participants.length < choosedCategory.participant_count))">
				<div class="col-lg-4">
					<button type="button" class="btn btn-default success px-5" @click="addParticipant">
						Добавить участника
					</button>
				</div>
			</div>

			<div class="row form-group justify-content-center mt-5">
				<div class="col-lg-5 border-top"></div>
			</div>

			<div class="row form-group justify-content-center mt-4">
				<div class="col-lg-4 default-text-style position-relative d-md-block">
					<h5 class="font-weight-bold">Сумма</h5>
					<span>
						@{{ choosedCategory ? choosedCategory.price * participants.length : '0.00' }} грн
					</span>
					<div class="position-relative mt-3">
							<span class="bg-not-accept-circle rounded-circle" style="padding-top: 0px;"><i
										class="fa fa-times" style="margin-left: 1px;"></i></span>
						<span class="default-text-style" style="margin-left: 25px">Не оплачена</span>
					</div>
				</div>
			</div>

			<div class="row form-group justify-content-center mt-5">
				<div class="col-lg-5 border-top"></div>
			</div>

			<div class="row form-group justify-content-center mt-4 mt-6"
			     v-if="choosedCategory && choosedCategory.without_track == 0">
				<div class="col-lg-8 offset-md-4">
					<span class="text-default-style track_name">
							<a href="/uploads/{{ $track->path ?: '' }}" target="_blank" v-if="listningTrack">
								@{{ track }}
							</a>
							<span v-else>
								@{{ track }}
							</span>
					</span>
					<label for="track" class="btn btn-default" v-if="!track ">
						Загрузить трек
					</label>
					<button type="button" class="btn btn-default danger ml-3" v-else @click.prevent="removeTrack()">
						Удалить трек
					</button>
					<input type="file" name="track" id="track" accept=".mp3" class="hidden">
				</div>
				<div class="col-lg-4">
					<div class="mt-4 default-text-style">Принимается mp3 файл, длительность трека @{{ choosedCategory.length | minuteConvertor }} минуты (+/- 5 сек)</div>
				</div>
			</div>

			<div class="row form-group justify-content-center mt-5 pt-4">
				<div class="col-lg-4">
					<button type="button" class="btn btn-default btn-block fz-17" data-target="#toPayModal"
					        data-toggle="modal"
					        :disabled="!selected_date_id || !selected_category_id || participants.length == 0 || hasEmptyField() || participants.length < choosedCategory.min_participant_count"
					        :class="!selected_date_id || !selected_category_id || participants.length == 0 || hasEmptyField() || participants.length < choosedCategory.min_participant_count ? 'invert' : ''"
					>
						Создать заявку
					</button>

					<button type="submit" hidden id="save"></button>
				</div>
			</div>
		</form>
	</div>
	<div class="modal fade" id="toPayModal" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<div class="h5 modal-title">Заявка создана!</div>
				</div>
				<div class="modal-body">
					<h5 class="text-center default-color">Вам необходиимо оплатить заявку. Вы можете сделать это сейчас или отложить на потом. Если потом, то возможность оплатить у вас будет в редактированиии заявки. Оплатить сейчас (мы перенаправим вас на сайт платежной системы)?</h5>
				</div>
				<div class="modal-footer">
					<button type="button" @click="saveWithoutPay" class="btn btn-danger"
					        data-dismiss="modal">Оплатить потом
					</button>
					<button type="button" @click="saveWithPay" class="btn btn-success"
					        data-dismiss="modal">Оплатить сейчас
					</button>
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

		.hidden {
			position: fixed;
			right: 100%;
			bottom: 100%;
		}

		.px-3 {
			padding-left: 1rem !important;
			padding-right: 1rem !important;
		}
	</style>
@endpush
@push('script')
	<script>
        new Vue( {
            el : '#app',
            data : {
                dates : {!! $dates !!},
                categories : {!! $categories !!},
                participants : {!! old('participants') ? json_encode(old('participants')) : '[]' !!},
                selected_date_id : '{{ old('date_id', $apl->date_id) }}',
                selected_category_id : '{{ old('category_id', $apl->category_id) }}',
                choosedCategory : {!! $choosedCategory ?: 'null' !!},
                track : '{!! $track ? $track->title : '' !!}',
                listningTrack : {{ $track ? true : 'null' }},
                apl : {!! $apl !!},
                pts : [],
                city : '{{ old('city', $apl->city ?: $user->city)  }}',
                country : '{{ old('country', $apl->country ?: $user->country) }}',
                crew : '{{ old('crew', $apl->crew ?: $user->crew) }}',
                school : '{{ (old('school', optional($apl->school)->title ?: optional($user->school)->title)) }}',
                pay : 0,
                remove_docs : [],
                remove_track : false,
                pts_remove : []
            },
            mounted : function () {
                var _this = this;
                $( '#form' ).on( 'change', '.document', function ( event ) {
                    var i = $( this ).data( 'i' );
                    _this.onChangeDocs( event, i );
                } );
                $( '#form' ).on( 'change', '#track', function ( event ) {
                    _this.setTrack( event );
                } );

                $( '#school' ).autocomplete( {
                    source : '/get-school',
                    minLength : 2
                } );

                if ( this.participants.length == 0 ) {
                    this.participants.push( { id : 0, name : '', is_paid : 0 } );
                }
                if ( this.selected_category_id ) {
                    this.categories.forEach( function ( item ) {
                        if ( item.id == _this.selected_category_id ) {
                            _this.choosedCategory = item;
                        }
                    } );
                }
                console.log( this.participants );
            },
            methods : {
                addParticipant : function () {
                    this.participants.push( { id : 0, name : '' } );
                },
                removeParticipant : function ( index ) {
                    this.pts_remove.push( this.participants[ index ].id );
                    this.participants.splice( index, 1 );
                },
                shortName : function ( title ) {
                    return title.replace( /^.+[\\\/]([^\\\/]+)$/, '$1' );
                },
                onChangeDocs : function ( event, index ) {
                    console.log( 'onChangeDocs', index );
                    this.participants[ index ].document = {
                        id : null,
                        path : event.target.value
                    };
                    this.$forceUpdate();
                    console.log( this.participants );
                },
                removeDoc : function ( index ) {
                    if ( this.participants[ index ].document.id ) {
                        this.remove_docs.push( this.participants[ index ].document.id );
                    }
                    this.participants[ index ].document = null;
                    $( '#document_' + index ).val( [] );
                    this.$forceUpdate();
                },
                setCategory : function () {
                    var _this = this;
                    this.categories.forEach( function ( item ) {
                        if ( item.id == _this.selected_category_id ) {
                            _this.choosedCategory = item;
                            _this.participants = [];
                            _this.participants.push( { id : 0, name : '', is_paid : 0 } );
                            $( '[name="documents[]"]' ).val( [] );
                        }
                    } );
                },
                setTrack : function ( event ) {
                    console.log( 'setTrack', event );
                    this.track = this.shortName( event.target.value );
                },
                removeTrack : function () {
                    this.remove_track = true;
                    this.track = '';
                    $( '#track' ).val( '' );
                },
                saveWithPay : function () {
                    this.pay = 1;
                    window.setTimeout( function () {
                        $( '#save' ).click();
                    }, 500 );
                },
                saveWithoutPay : function () {
                    this.pay = 0;
                    window.setTimeout( function () {
                        $( '#save' ).click();
                    }, 500 );
                },
                hasEmptyField : function () {
                    var result = false;
                    this.participants.forEach( function ( item ) {
                        if ( item == '' ) {
                            result = true;
                            return false;
                        }
                    } );
                    return result;
                }
            },
            filters : {
                minuteConvertor : function ( value ) {
                    var minute = parseInt( value / 60 ),
                        seconds = ( value % 60 ) < 10 ? '0' + value % 60 : value % 60;
                    return ( minute + ':' + seconds );
                }
            }
        } );
	</script>
@endpush
