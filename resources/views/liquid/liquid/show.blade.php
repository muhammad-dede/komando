@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
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

        .comp-tab .nav-item .disabled {
            cursor: not-allowed;
            opacity: .5;
        }
    </style>


@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Show Liquid #{{ $liquid->id }}</h4>
        </div>
        <div class="col-md-6 col-xs-12 lh-70 align-right">
            <a href="{{ url('liquid/'.Request::segment(2).'/edit') }}" class="btn btn-warning"><em class="fa fa-pencil"></em> Edit Liquid</a>
            <a href="{{ url('dashboard-admin/liquid-jadwal') }}" class="btn btn-primary"><em class="fa fa-arrow-right"></em> Back Dashboard</a>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                @include('components.liquid-tab-show', ['active' => 'liquid'])
                <div class="tab-content comp-tab-content">
                    <form method="POST" action="{{ route('liquid.update', $liquid) }}">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label>Jadwal Feedback <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="input-daterange input-group" data-daterange>
                                            <input disabled type="text" class="form-control" name="feedback_start_date"
                                                   value="{{ old('feedback_start_date', $liquid->feedback_start_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0">to</span>
                                            <input disabled type="text" class="form-control" name="feedback_end_date"
                                                   value="{{ old('feedback_end_date', $liquid->feedback_end_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                        </div>
                                        <div class="badge-warning">Feedback dilakukan selama 7 hari sejak tanggal
                                            dimulai
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jadwal Penyelarasan <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="input-daterange input-group" data-daterange>
                                            <input disabled type="text" class="form-control" name="penyelarasan_start_date"
                                                   value="{{ old('penyelarasan_start_date', $liquid->penyelarasan_start_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0">to</span>
                                            <input disabled type="text" class="form-control" name="penyelarasan_end_date"
                                                   value="{{ old('penyelarasan_end_date', $liquid->penyelarasan_end_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jadwal Pengukuran Pertama <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="input-daterange input-group" data-daterange>
                                            <input disabled type="text" class="form-control" name="pengukuran_pertama_start_date"
                                                   value="{{ old('pengukuran_pertama_start_date', $liquid->pengukuran_pertama_start_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0">to</span>
                                            <input disabled type="text" class="form-control" name="pengukuran_pertama_end_date"
                                                   value="{{ old('pengukuran_pertama_end_date', $liquid->pengukuran_pertama_end_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                        </div>
                                        <div class="badge-warning">Pengukuran pertama dilakukan selama 7 hari sejak
                                            tanggal mulai
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jadwal Pengukuran Kedua <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="input-daterange input-group" data-daterange>
                                            <input disabled type="text" class="form-control" name="pengukuran_kedua_start_date"
                                                   value="{{ old('pengukuran_kedua_start_date', $liquid->pengukuran_kedua_start_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0">to</span>
                                            <input disabled type="text" class="form-control" name="pengukuran_kedua_end_date"
                                                   value="{{ old('pengukuran_kedua_end_date', $liquid->pengukuran_kedua_end_date->format('d-m-Y')) }}"
                                                   autocomplete="off"/>
                                        </div>
                                        <div class="badge-warning">Pengukuran kedua dilakukan 3 - 6 bulan dari
                                            pengukuran pertama dan dilakukan selama 7 hari sejak tanggal dimulai.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Reminder Aksi Resolusi <span
                                                class="text-danger">*</span></label>

                                    <div>
                                        {!!
                                            Form::select(
                                                'reminder_aksi_resolusi',
                                                $reminderOptions,
                                                old('reminder_aksi_resolusi', $liquid->reminder_aksi_resolusi),
                                                [
                                                    'class' => 'select2 form-control',
                                                    'disabled' => 'disabled'
                                                ]

                                            )
                                        !!}
                                    </div>
                                    <div class="badge-warning">Memberikan notifikasi sebagai pengingat aksi resolusi
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
        });
    </script>
@stop
