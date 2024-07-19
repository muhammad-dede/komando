@extends('layout')

@push('styles')
    <link href="{{ asset('assets/css/badge.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/card.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/image.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
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
    <div class="card">
        <div class="card-header bg-blue">
            <div class="title-top color-white">Atasan yang Dinilai</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-3 col-xl-2 col-xs-2 align-center">
                            <img src="{{ app_user_avatar($dataAtasan->nip) }}" alt="user" width="200"
                                class="radius-full img-fluid mx-auto d-block">
                        </div>
                        <div class="col-md-9 col-xs-10">
                            <table class="table table-striped">
                                <tr>
                                    <td width="100px">Nama</td>
                                    <td width="10px">:</td>
                                    <td>{{ $dataAtasan->name }}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>:</td>
                                    <td>{{ $dataAtasan->nip }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $dataAtasan->ad_title }}</td>
                                </tr>
                                <tr>
                                    <td>Kantor</td>
                                    <td>:</td>
                                    <td>{{ $dataAtasan->business_area }} - {{ $dataAtasan->ad_company }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('pengukuran-kedua.store') . '?liquid_peserta_id=' . request()->get('liquid_peserta_id') }}"
        method="post">
        {{ csrf_field() }}
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
                        <div class="mar-b-1rem font-500">
                            Pengukuran ini bersifat ANONYMOUS dan atasan tidak dapat melihat nama penilai
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
                                    <th class="align-center" width="30%">
                                        {{ $label->ukuran }} <em class="fa fa-star color-star"></em>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resolusi as $index => $item)
                                    <tr>
                                        <td>
                                            {{ $item }}
                                        </td>
                                        <td>{!! $penilaianPertama[$index] !!}</td>
                                        <td style="padding: 1rem 2rem">
                                            <input class="input-range slider-primary"
                                                name="{{ 'resolusi_' . ($index + 1) }}" data-slider-id='ex1Slider'
                                                type="text" data-slider-min="0" data-slider-tooltip="always"
                                                data-slider-max="10" data-slider-step="1" data-slider-value="0" value="" />
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
                        Save
                    </button>
                    <a href="#" class="btn btn-warning btn-lg pull-right">
                        <em class="fa fa-times"></em> Cancel
                    </a>
                </div>
            </div>
        </div>


    </form>
@stop

@push('scripts')
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
@endpush
