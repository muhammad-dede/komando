@extends('layout')

@push('styles')
    <link href="{{ asset('assets/css/badge.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/card.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/image.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.css') }}">
    <style>
        .slider-selection {
            background: #0099FA !important;
        }

        .slider.slider-horizontal {
            width: 100% !important;
            height: 20px;
        }

        .slider-handle {
            background-color: #fff !important;
            background-image: none !important;
            -webkit-box-shadow: 1px 1px 24px -2px rgba(0, 0, 0, 0.75) !important;
            -moz-box-shadow: 1px 1px 24px -2px rgba(0, 0, 0, 0.75) !important;
            box-shadow: 1px 1px 24px -2px rgba(0, 0, 0, 0.75) !important;
        }

        .slider .tooltip.top {
            margin-top: -21px;
            padding: 0px;
            font-size: 9px;
        }

        .tooltip-inner {
            border-radius: 100px !important;
            height: 20px;
            border: none;
            background-color: #0099FA !IMPORTANT;
            width: 20px;
            padding: 3px 8px;
        }

    </style>
@endpush

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
                        <img src="{{ app_user_avatar($dataAtasan['nip']) }}" alt="user" width="200"
                            class="radius-full img-fluid mx-auto d-block">

                    </div>
                    <div class="col-md-9 col-xl-10 col-xs-8 word-break">
                        <button class="btn btn-blue-2">ATASAN YANG DINILAI</button>
                        <div class="name">{{ $dataAtasan['nama'] }}</div>
                        <span class="text">NIP : {{ $dataAtasan['nip'] }}</span> <span class="text">JABATAN :
                            {{ $dataAtasan['jabatan'] }}</span>
                        <span class="text">KANTOR : {{ $dataAtasan['business_area'] }} -
                            {{ $dataAtasan['unit']['name'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('pengukuran-kedua.update', $dataPenilaian->id) }}" method="post">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-blue">
                        <div class="title-top color-white">Pengukuran Kedua</div>
                    </div>
                    <div class="card-body">
                        <div class="mar-b-1rem">
                            Pengukuran kedua merupakan evaluasi dari sikap atasan setelah melakukan activity log book.
                            Pengukuran ini akan mengevaluasi apakah sikap atasan mengalami peningkatan, sama, atau menurun
                            dari pengukuran pertama. Pengukuran menggunakan Skala Likert dengan keterangan sebagai berikut:
                        </div>
                        <div class="mar-b-1rem">
                            <button class="btn btn-danger btn-sm bold-text f-12">Sangat Jarang (0-2)</button>
                            <button class="btn btn-warning btn-sm bold-text f-12">Jarang (3-4)</button>
                            <button class="btn btn-success btn-sm bold-text f-12">Kadang-kadang (5-6)</button>
                            <button class="btn btn-primary bg-blue-2 btn-sm bold-text f-12">Sering (7-8)</button>
                            <button class="btn btn-primary btn-sm bold-text f-12">Sangat Sering (9-10)</button>
                        </div>
                        <div class="mar-b-1rem">
                            Silahkan beri penilaian pada atasan anda berdasarkan 3 indikator sikap berikut!
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ $label->indikator }}</th>
                                    <th>Nilai {{ $label->ukuran1 }}</th>
                                    <th class="align-center">
										{{ $label->ukuran }} <em class="fa fa-star color-star"></em>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($resolusi as $index => $item)
                                    @php
                                        $choosenRes = $dataPenilaian->toArray()['resolusi'];
                                        $resIndex = array_map(function ($item) use ($index) {
                                            return (int) explode(':', $item)[0] === $index;
                                        }, $choosenRes);

                                        $filledRes = explode(':', $dataPenilaian->resolusi[array_search(true, $resIndex)])[1];
                                        $idRes = explode(':', $dataPenilaian->resolusi[array_search(true, $resIndex)])[0];

                                        if (isset($dataPenilaian->alasan[$index])) {
                                            $alasan = explode(':', $dataPenilaian->alasan[$index]);
                                        } else {
                                            $alasan = [null, null];
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $item }}
                                        </td>
                                        <td>
                                            {!! $penilaianPertama[$i++] !!}
                                        </td>
                                        <td align="center">
                                            <input class="input-range slider-primary" data-index="{{ $index }}"
                                                name="{{ 'resolusi_' . $index }}" data-slider-id='ex1Slider' type="text"
                                                data-slider-min="0" data-slider-tooltip="always" data-slider-max="10"
                                                data-slider-step="1" data-slider-value="{{ $filledRes }}"
                                                value="{{ $filledRes }}" />
                                            <input type="hidden" name="{{ 'resolusi_alasan_' . $index }}"
                                                id="{{ 'resolusi_alasan_' . $index }}"
                                                value="{{ $idRes }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-20">
            <div class="col-md-6">
            </div>
            <div class="col-md-6 pull-right">
                <div class="button-list">
                    <button type="submit" class="btn btn-primary btn-lg pull-right"><em class="fa fa-save"></em>
                        Update
                    </button>
                    <button type="button" class="btn btn-danger btn-lg pull-right"
                        onclick="window.location.href='{{ url()->previous() }}';">
                        <em class="fa fa-times"></em>
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="alasan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="title title-top" id="exampleModalLabel">Alasan Memilih <em
                                class="fa fa-star color-star"></em> 1</span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">alasan :</label>
                            <textarea class="form-control" id="res-alasan" rows="7"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="tambah-kelebihan">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('javascript')
    <script src="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let inputIndex,
                inputAlasan = $('textarea#res-alasan'),
                alasanVal;

            $('input[type=radio][value=RENDAH_SEKALI]').on('click', function() {
                inputIndex = $(this).attr('data-index')
                alasanVal = $(`input[name=resolusi_alasan_${inputIndex}]`)

                inputAlasan.val(
                    alasanVal.val()
                )
            });

            $('.input-range').change(function() {
                var value = $(this).val();
                console.log(value)
                if (value == 10) {
                    $('#alasan').modal('show');
                }
                inputIndex = $(this).attr('data-index')
                alasanVal = $(`input[name=resolusi_alasan_${inputIndex}]`)

                inputAlasan.val(
                    alasanVal.val()
                )
            });

            $('#tambah-kelebihan').click(function() {
                alasanVal
                    .val(
                        inputAlasan.val()
                    )
            })
            $('.input-range').each(function() {
                var value = $(this).attr('data-slider-value');
                var separator = value.indexOf(',');
                if (separator !== -1) {
                    value = value.split(',');
                    value.forEach(function(item, i, arr) {
                        arr[i] = parseFloat(item);
                    });
                } else {
                    value = parseFloat(value);
                }
                $(this).slider({
                    formatter: function(value) {
                        return value;
                    },
                    min: parseFloat($(this).attr('data-slider-min')),
                    max: parseFloat($(this).attr('data-slider-max')),
                    range: $(this).attr('data-slider-range'),
                    value: value,
                    tooltip_split: $(this).attr('data-slider-tooltip_split'),
                    tooltip: $(this).attr('data-slider-tooltip')
                });
            });
        });
    </script>
@stop
