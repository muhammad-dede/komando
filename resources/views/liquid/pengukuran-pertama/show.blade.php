@extends('layout')

@section('css')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.css') }}">
    <style>

        .slider-selection {
            background: #0099FA !important;
        }
        .slider.slider-horizontal {
            width: 100% !important;
            height: 50px !important;
        }
        .slider-handle {
            background-color: #fff !important;
            background-image: none !important;
            -webkit-box-shadow: 1px 1px 24px -2px rgba(0,0,0,0.75) !important;
            -moz-box-shadow: 1px 1px 24px -2px rgba(0,0,0,0.75) !important;
            box-shadow: 1px 1px 24px -2px rgba(0,0,0,0.75) !important;
        }
        .slider .tooltip.top {
            margin-top: -7px !important;
            padding: 0px;
            font-size: 9px;
        }
        .tooltip-inner {
            border-radius: 100px !important;
            height: 20px;
            border: none;
            background-color: #0099FA !IMPORTANT;
            width: 20px;
            padding: 3px 6px !important;
        }
        .slider.slider-horizontal {
            pointer-events: none;
        }
    </style>
@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Penilaian Atasan</h4>
        </div>
    </div>
    <div class="card-box feedback">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-md-3 col-xl-2 col-xs-4 align-center">
                        <img src="{{ app_user_avatar($dataAtasan->nip) }}"
                             alt="user"
                             width="200"
                             class="radius-full img-fluid mx-auto d-block">
                    </div>
                    <div class="col-md-9 col-xl-10 col-xs-8 word-break">
                        <button class="btn btn-blue-2">ATASAN YANG DINILAI</button>
                        <div class="name">{{ $dataAtasan->name }}</div>
                        <span class="text">NIP : {{ $dataAtasan->nip }}</span> <span class="text">JABATAN : {{ $dataAtasan->ad_title }}</span>
                        <span class="text">KANTOR : {{ $dataAtasan->business_area }} - {{ $dataAtasan->ad_company }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <form>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-blue">
                        <div class="title-top color-white">Pengukuran Pertama</div>
                    </div>
                    <div class="card-body">
                        <div class="mar-b-1rem">
                            Pengukuran ini merupakan pengukuran awal dari sikap atasan dimana sikap tersebut akan dievaluasi
                            lagi pada pengukuran kedua. Pengukuran menggunakan Skala Likert dengan keterangan sebagai berikut:
                            Silahkan beri penilaian pada atasan anda berdasarkan 3 indikator sikap berikut
                        </div>
                        <div class="mar-b-1rem">
                            <button class="btn btn-danger btn-sm bold-text f-12">Sangat Jarang (0-2)</button>
                            <button class="btn btn-warning btn-sm bold-text f-12">Jarang (3-4)</button>
                            <button class="btn btn-success btn-sm bold-text f-12">Kadang-kadang (5-6)</button>
                            <button class="btn btn-primary bg-blue-2 btn-sm bold-text f-12">Sering (7-8)</button>
                            <button class="btn btn-primary btn-sm bold-text f-12">Sangat Sering (9-10)</button>
                        </div>
                        <div class="mar-b-1rem">
                            Silahkan beri penilaian pada atasan berdasarkan 3 indikator sebagai berikut!
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead >
                            <tr>
                                <th>{{ $label->indikator }}</th>
                                <th class="align-center">{{ $label->ukuran }} <em class="fa fa-star color-star"></em></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($resolusi as $index => $item)
                                @php
                                    //TODO beware of undefined index
									$filledRes = explode(':', $dataPenilaian->resolusi[$index])[1];
									$alasan = [null, null];
									if (isset($dataPenilaian->alasan[$index])) {
										$alasan    = explode(':', $dataPenilaian->alasan[$index]);
									}
                                @endphp
                                <tr>
                                    <td>
                                        {{ $item }}
                                    </td>
                                    <td>
                                        <input class="input-range slider-primary" name="{{ 'resolusi_'.($index+1) }}" data-slider-id='ex1Slider' type="text" data-slider-min="0"
                                            data-slider-tooltip="always" data-slider-max="10" data-slider-step="1"
                                            data-slider-value="{{ $filledRes }}" value="{{ $filledRes }}"/>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-20 mar-b-2rem">
            <div class="col-md-6">
            </div>
            <div class="col-md-6 pull-right">
                <div class="button-list">
                    <a href="{{ route('penilaian.edit', $dataPenilaian->id) }}" class="btn btn-warning btn-lg pull-right"><em
                                class="fa fa-save"></em>
                        Edit
                    </a>
                    <a href="{{ url('penilaian?liquid_id='.$liquidId) }}" class="btn btn-danger btn-lg pull-right"><i
                                class="fa fa-arrow-left"></i>
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade" id="resolusi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header border-unset">
                    <span class="title title-top" id="exampleModalLabel">Alasan Memilih <em class="fa fa-star color-star"></em></span> 10
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">alasan :</label>
                            <textarea class="form-control" id="message-text" rows="7"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-unset">
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable();
            $('.input-range').change(function () {
                var value = $(this).val();
                console.log(value)
                if(value == 10) {
                    $('#resolusi').modal('show');
                }
            });
            let inputIndex,
                inputAlasan = $('textarea#res-alasan'),
                alasanVal;

            $('input[type=radio][value=SANGAT_TINGGI]').on('click', function () {
                inputIndex	= $(this).attr('data-index')
                alasanVal	= $(`input[name=resolusi_alasan_${inputIndex}]`)

                inputAlasan.val(
                    alasanVal.val()
                )
            });

            $('#tambah-kelebihan').click(function () {
                alasanVal
                    .val(
                        inputAlasan.val()
                    )
            })
            $( '.input-range').each(function(){
                var value = $(this).attr('data-slider-value');
                var separator = value.indexOf(',');
                if( separator !== -1 ){
                    value = value.split(',');
                    value.forEach(function(item, i, arr) {
                        arr[ i ] = parseFloat( item );
                    });
                } else {
                    value = parseFloat( value );
                }
                $( this ).slider({
                    formatter: function(value) {
                        return value;
                    },
                    min: parseFloat( $( this ).attr('data-slider-min') ),
                    max: parseFloat( $( this ).attr('data-slider-max') ),
                    range: $( this ).attr('data-slider-range'),
                    value: value,
                    tooltip_split: $( this ).attr('data-slider-tooltip_split'),
                    tooltip: $( this ).attr('data-slider-tooltip')
                });
            });
        });

    </script>
@stop
