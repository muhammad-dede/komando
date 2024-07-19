@extends('layout')

@section('css')
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

        .datepicker table tr td.disabled{
            color:#dedede;
        }
    </style>


@stop

@section('title')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h4 class="page-title">Create Liquid</h4>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <ul class="nav nav-tabs comp-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">1. Tanggal LIQUID</a>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link disabled">2. Unit Kerja</div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link disabled">3. Peserta Penilaian</div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link disabled">4. Dokumen</div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link disabled">5. Info Gathering (Opsional)</div>
                    </li>
                </ul><!-- Tab panes -->
                <div class="tab-content comp-tab-content">
                    @if(!$kelebihanKekuranganId)
                        <div class="alert alert-warning">
                            Liquid baru bisa dibuat jika sudah ada <strong>Kelebihan Kekurangan yang aktif</strong>.
                            Saat ini belum ada Kelebihan Kekurangan aktif.
                            <a class="btn btn-primary" href="{{ route('master-data.kelebihan-kekurangan.create') }}">
                                <em class="fa fa-plus"></em>
                                Tambah Kelebihan Kekurangan
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('liquid.store') }}" id="formCreateLiquid">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Jadwal Feedback <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="input-daterange input-group" data-daterange>
                                                <input type="text" class="form-control" name="feedback_start_date"
                                                       value="{{ old('feedback_start_date') }}"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon bg-custom b-0">to</span>
                                                <input type="text" class="form-control" name="feedback_end_date"
                                                       value="{{ old('feedback_end_date') }}"
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
                                                <input type="text" class="form-control" name="penyelarasan_start_date"
                                                       value="{{ old('penyelarasan_start_date') }}"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon bg-custom b-0">to</span>
                                                <input type="text" class="form-control" name="penyelarasan_end_date"
                                                       value="{{ old('penyelarasan_end_date') }}"
                                                       autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Jadwal Pengukuran Pertama <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="input-daterange input-group" data-daterange>
                                                <input type="text" class="form-control" name="pengukuran_pertama_start_date"
                                                       value="{{ old('pengukuran_pertama_start_date') }}"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon bg-custom b-0">to</span>
                                                <input type="text" class="form-control" name="pengukuran_pertama_end_date"
                                                       value="{{ old('pengukuran_pertama_end_date') }}"
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
                                                <input type="text" class="form-control" name="pengukuran_kedua_start_date"
                                                       value="{{ old('pengukuran_kedua_start_date') }}"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon bg-custom b-0">to</span>
                                                <input type="text" class="form-control" name="pengukuran_kedua_end_date"
                                                       value="{{ old('pengukuran_kedua_end_date') }}"
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
                                                    old('reminder_aksi_resolusi'),
                                                    [
                                                        'class' => 'select2 form-control'
                                                    ]
                                                )
                                            !!}
                                        </div>
                                        <div class="badge-warning">Memberikan notifikasi sebagai pengingat aksi resolusi
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg pull-right">
                                        <i aria-hidden="true" class="fa fa-arrow-right"></i> Next
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
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

            const sdRange = parseInt(`{{ env('SCHEDULE_DEFAULT_RANGE') }}`);
            const p1p2Range = parseInt(`{{ env('SCHEDULE_DEFAULT_WEEK_RANGE_P1_P2') }}`);

            const addWeekdays = (date, days) => {
                date = moment(date, 'DD-MM-YYYY');
                while (days > 0) {
                    date = date.add(1, 'days');
                    if (date.isoWeekday() !== 6 && date.isoWeekday() !== 7) {
                        days -= 1;
                    }
                }
                datestring = date.format('DD-MM-YYYY');
                return datestring;
            }

            let forms = ['feedback_end_date', 'penyelarasan_start_date', 'penyelarasan_end_date', 'pengukuran_pertama_start_date', 'pengukuran_pertama_end_date', 'pengukuran_kedua_start_date', 'pengukuran_kedua_end_date'];

            $("[name=feedback_start_date]").one('changeDate', function(e){
                var cur = moment(e.target.value, 'DD-MM-YYYY')
                forms.map((f,i) => {
                    var range;
                    if (i%2 > 0){
                        if (f.includes('pengukuran_kedua_start_date')){
                            cur = addWeekdays(cur, p1p2Range*5) // pengukuran_kedua_start_date 3 bulan (13 pekan sesuai meet 20/07/2020) setelah pengukuran_pertama_end_date
                        }
                        range = addWeekdays(cur, 1)
                    } else {
                        range = addWeekdays(cur, sdRange)
                    }
                    setTimeout(() => { // FIXME override end_date value triggered by bootstrap datepicker when start_date changed, temp solution :V
                        $(`[name=${f}]`).datepicker("setDate", range);
                    }, 10);
                    cur = moment(range, 'DD-MM-YYYY');
                })
            });

            let startDate = new Date()
            @if(!config('liquid.disable_date_validation'))
                startDate.setDate(startDate.getDate() + 1)
            @endif

            $('.ui.search.dropdown').dropdown();
            $(".select2").select2();
            jQuery('[class^=input-daterange]').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                weekStart: 1,
                startDate: startDate
            });
        });
    </script>
@stop
