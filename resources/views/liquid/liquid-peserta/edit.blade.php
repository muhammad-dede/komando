@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .ui.search.dropdown.selection.multiple .ui.label {
            color: #606060;
        }

        .select2 {
            width: 100% !important;
        }

        i.icon.delete:before {
            content: '';
            background: url({{ asset('assets/images/clear.svg') }}) !important;
            background-size: cover !important;
            position: absolute;
            width: 15px;
            height: 15px;
            margin-left: 0px;
        }

        .ui.label > .close.icon, .ui.label > .delete.icon {
            cursor: pointer;
            margin-right: 0;
            padding-right: 5px;
            margin-left: .5em;
            font-size: .92857143em;
            opacity: .5;
            -webkit-transition: background .1s ease;
            transition: background .1s ease;
        }

        .ui.label > .icon {
            width: auto;
            margin: 0 .75em 0 0;
        }

        .ms-container#ms-my_multi_select3, .ms-container#ms-my_multi_select4 {
            max-width: 600px !important;
        }
    </style>


@stop

@section('title')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h4 class="page-title">Edit Peserta Liquid #{{ $liquid->id }}</h4>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row peserta">
        <div class="col-md-12 col-xs-12">
            <div class="card-box">
                @include('components.liquid-tab', ['active' => 'peserta'])
                <div class="tab-content comp-tab-content">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success mar-b-1rem" data-toggle="modal"
                                    data-target="#modalPilihAtasan">
                                <em class="fa fa-plus"></em>
                                Tambah Atasan
                            </button>
                        </div>
                        <div class="col-md-6">
                            <form
                                    id="formBulkDeletePeserta"
                                    method="POST" action="{{ route('liquid.peserta.destroy', [$liquid, 0]) }}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button
                                        type="submit"
                                        name="action"
                                        value="bulk"
                                        onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        class="btn btn-danger mar-b-1rem float-right disabled">
                                    Hapus <span data-counter-peserta-terpilih></span> Peserta Terpilih
                                </button>
                            </form>
                        </div>
					</div>
					<form action="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), $liquid->id) }}" method="get">
						<div class="row" style="margin-bottom: 12px">
							<div class="col-md-6 col-xs-12 lh-70"></div>
							<div class="col-md-4 col-xs-12 lh-70">
								<div class="lh-70 float-right width-full">
									<input type="text" class="form-control" name="search"
										placeholder="search peserta"
										value="{{ request('search', '') }}">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<button type="submit" class="btn btn-primary width-full">Search</button>
							</div>
						</div>
					</form>
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 12px">
                            @include('liquid.liquid-peserta._tabel_peserta', ['editable' => true])
                        </div>
                        <div class="col-xs-12">
                            <a href="{{ url('liquid/'.Request::segment(2).'/dokumen/edit') }}"
                               class="btn btn-primary btn-lg pull-right">
                                <i aria-hidden="true" class="fa fa-arrow-right"></i> Next
                            </a>
                            <a href="{{ url('liquid/'.Request::segment(2).'/unit-kerja/edit') }}"
                               class="mar-r-1rem btn btn-warning btn-lg pull-right">
                                <i aria-hidden="true" class="fa fa-arrow-left"></i> Previous
                            </a>
                        </div>
					</div>
                </div>
            </div>
        </div>
    </div>
    @include('liquid.liquid-peserta._modal-pilih-atasan')
    @include('liquid.liquid-peserta._modal-pilih-atasan-pengganti')
    @include('liquid.liquid-peserta._modal-pilih-bawahan', ['listBawahan' => $listBawahan])

@stop

@section('javascript')
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2({
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route('api.pegawai.index') }}',
                    dataType: 'json',
                    delay: 800,
                }
            });

            $('[data-role="tambahBawahan"]').on('click', function (e) {
                $('#formTambahBawahan input[name="atasan_id"]').val($(e.currentTarget).data('atasan_id'));
            });

            $('[data-role="buttonGantiAtasan"]').on('click', function (e) {
                $('#formGantiAtasan input[name="atasan_lama"]').val($(e.currentTarget).data('atasan_id'));
                $('#formGantiAtasan select[name="atasan_baru"]').val($(e.currentTarget).data('atasan_id'));
                $('#formGantiAtasan select[name="atasan_baru"]').trigger('change');
            });

            //bulk delete
            $('input[name="pesertaIds[]"]').on('change', function(){
                let countSelected = $('input[name="pesertaIds[]"]:checked').length;
                if (countSelected > 0) {
                    $('[data-counter-peserta-terpilih]').html(countSelected).parent().removeClass('disabled')
                } else {
                    $('[data-counter-peserta-terpilih]').html('').parent().addClass('disabled')
                }
            });

        });
    </script>
@stop
