@extends('layout')

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Code of Conduct</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <table>
                            <tr>
                                <td>
                                    <span class="display-1">{{$tanggal->format('d')}}</span>
                                </td>
                                <td style="padding-left: 10px;">
                                    <span style="font-size: 24px">{{$tanggal->format('F')}}</span><br>
                                    <span style="font-size: 24px" class="text-muted">{{$tanggal->format('l')}}</span>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        @if(Auth::user()->can(['input_tema_nasional', 'input_materi_pusat', 'input_materi_gm', 'input_coc_local']))
                            <div class="button-list">

                                @if(Auth::user()->can('input_tema_nasional'))
                                    <a href="javascript:" data-toggle="modal" data-target="#thematicModal"
                                       class="btn btn-success waves-effect waves-light"><i class="fa fa-globe"></i> Tema</a>
                                @endif
                                @if(Auth::user()->can('input_materi_pusat') || Auth::user()->can('input_materi_gm'))
                                    <div class="btn-group">

                                        <button type="button"
                                                class="btn btn-info dropdown-toggle waves-effect waves-light btn-block"
                                                data-toggle="dropdown" aria-expanded="true"><i class="fa fa-book"></i>
                                            Materi<span class="m-l-5"></span></button>
                                        <div class="dropdown-menu">
                                            @if(Auth::user()->can('input_materi_pusat'))
                                                <a id="materiNasionalHref" class="dropdown-item" href="{{url('coc/create/materi/nasional')}}">Materi
                                                    Nasional</a>
                                            @endif
                                            @if(Auth::user()->can('input_materi_gm'))
                                                <a class="dropdown-item" href="{{url('coc/create/materi/gm')}}">Materi
                                                    GM</a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if(Auth::user()->can('input_coc_local'))
                                    <a href="{{url('coc/create/local')}}"
                                        class="btn btn-primary waves-effect waves-light"><i
                                                class="fa fa-calendar"></i> Jadwal CoC</a>
                                @endif
                            </div>
                        @endif

                            @if(Auth::user()->can('input_coc_local'))
                                <a href="{{url('coc/list/admin')}}" class="btn btn-success btn-block m-t-20">Complete CoC</a>
                            @endif
                    </div>
                </div>

                <hr>
                <div class="row">
                    <h4 class="col-md-12 card-title">{{@Auth::user()->getOrgLevel()->stext}}</h4>
                </div>
                <a href="{{url('coc/list')}}" class="btn btn-info btn-block m-b-20">Display All CoC</a>
                @if($coc_today->count() == 0)
                    <div class="row">
                        <span class="col-md-12 card-title text-muted">Tidak ada CoC hari ini</span>
                    </div>
                @else
                    <div style="overflow: auto; height: 500px;">
                    @foreach($coc_today as $coc)
                        <div class="row m-b-20">
                            <div class="col-md-12 col-xs-12">

                                <div class="card">
                                    @if(@$coc->materi->jenisMateri->jenis=='Nasional')
                                    <div class="card-block card-inverse card-danger">
                                    @elseif(@$coc->materi->jenisMateri->jenis=='GM')
                                    <div class="card-block card-inverse card-warning">
                                    @else
                                    <div class="card-block card-inverse card-primary">
                                    @endif
                                    <h4 class="card-title">{{$coc->judul}}</h4>
                                </div>

                                <ul class="list-group list-group-flush">
                                    @if($coc->scope!='nasional')
                                        <li class="list-group-item">
                                            <div class="row">
                                                <span class="col-xs-3">
                                                    <img src="{{(@$coc->pemateri->user->foto!='') ? url('user/foto-thumb/'.@$coc->pemateri->user->id) : asset('assets/images/user.jpg')}}"
                                                            alt="User"
                                                            class="img-thumbnail" width="45">
                                                </span>
                                                <span class="col-xs-9">
                                                    {{@$coc->pemateri->name}}
                                                    <br>
                                                    <small class="text-muted">{{@$coc->pemateri->nip}}
                                                        <br>
                                                        {{@$coc->pemateri->jabatan}}</small>
                                                </span>
                                            </div>

                                        </li>
                                    @endif
                                    <li class="list-group-item"><i
                                                class="fa fa-institution"></i> {{@$coc->organisasi->stext}}
                                    </li>
                                    <li class="list-group-item"><i
                                                class="fa fa-map-marker"></i> {{$coc->lokasi}}
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <span class="col-xs-5">
                                                <i class="fa fa-clock-o"></i> {{$coc->tanggal_jam->format('h:i A')}}
                                            </span>
                                            <span class="col-xs-7">
                                                <i class="fa fa-user"></i> {{$coc->attendants->count()}}/{{$coc->jml_peserta}} ({{number_format(($coc->attendants->count()/$coc->jml_peserta)*100, 2)}}%)
                                            </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                        <span class="col-xs-5">
                                            @if($coc->status=='COMP')
                                                <i class="fa fa-lock text-danger"></i> <span class="text-danger">CLOSED</span>
                                            @else
                                                <i class="fa fa-unlock"></i> OPEN
                                            @endif
                                        </span>
                                        <span class="col-xs-7">
                                            @if($coc->checkAtendant(Auth::user()->id) || $coc->status=='COMP')
                                                <a href="{{url('coc/event/'.$coc->id)}}"
                                                    class="card-link"><i class="fa fa-info-circle"></i>
                                            More detail</a>
                                            @else
                                                <a href="{{url('coc/check-in/'.$coc->id)}}"
                                                    class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i>
                                            Check-In</a>
                                            @endif
                                        </span>
                                        </div>
                                    </li>
                                </ul>
                                </div>

                            </div>
                        </div>
                    @endforeach
                    </div>
                @endif
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card-box">
                        {!! $calendar->calendar() !!}
                    </div>
                </div>
            </div>

            <!-- sample modal content -->
            <div id="thematicModal" class="modal fade" role="dialog" aria-labelledby="thematicModalLabel"
                 aria-hidden="true">
                {!! Form::open(['url'=>'coc/apply-thematic']) !!}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            <h4 class="modal-title" id="myModalLabel">Apply Thematic</h4>
                        </div>
                        <div class="modal-body">
                            <div class="m-l-20">
                                <div class="form-group">
                                    <label>Tanggal</label>

                                    <div>
                                        <div class="input-daterange input-group" id="date-range">
                                            <input type="text" class="form-control" name="start_date" autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0">to</span>
                                            <input type="text" class="form-control" name="end_date" autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tema" class="form-control-label">Tema</label>

                                    <div>
                                        {!! Form::select('tema_id', $tema_list, null, ['class'=>'form-control select2', 'id'=>'tema_id', 'width'=>'100%', 'style'=>'width: 100% !important; padding: 0; z-index:10000;']) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                        class="fa fa-check"></i>
                                Apply
                            </button>
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
                {!! Form::close() !!}
            </div><!-- /.modal -->

            <!-- sample modal content -->
            <div id="cocModal" class="modal fade" role="dialog" aria-labelledby="cocModalLabel"
                 aria-hidden="true">
                {!! Form::open(['url'=>'coc/create']) !!}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            <h4 class="modal-title" id="myModalLabel">Check In</h4>
                        </div>
                        <div class="modal-body">
                            <div class="m-l-20">

                                <div class="form-group">
                                    <label for="tanggal"
                                           class="form-control-label">Tanggal</label>

                                    <div>

                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                                   id="coc_date"
                                                   name="tanggal_coc">
                                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                        </div>

                                        <!-- input-group -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jam"
                                           class="form-control-label">Jam</label>

                                    <div>

                                        <div class="input-group clockpicker" data-placement="top" data-align="top"
                                             data-autoclose="true">
                                            <input type="text" class="form-control" placeholder="Masukkan Jam" id="jam"
                                                   name="jam">
                                            <span class="input-group-addon"> <span
                                                        class="zmdi zmdi-time"></span> </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pemateri_id" class="form-control-label">Pemateri</label>

                                    <div>
                                        <select class="itemName form-control" name="pernr_pemateri"
                                                style="width: 100% !important; padding: 0; z-index:10000;"></select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="topik" class="form-control-label">Topik</label>

                                    <div>
                                        {!! Form::text('topik', null, ['class'=>'form-control', 'id'=>'topik', 'placeholder'=>'Topik CoC']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="topik" class="form-control-label">Deskripsi</label>

                                    <div>
                                        {!! Form::textarea('deskripsi', null, ['class'=>'form-control', 'id'=>'deskripsi',
                                                'placeholder'=>'Masukkan Deskripsi CoC', 'rows'=>'3']) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"><i
                                        class="fa fa-save"></i>
                                Save
                            </button>
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Close
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
                {!! Form::close() !!}
            </div><!-- /.modal -->

            @stop

            @section('javascript')
                <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
                <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
                <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
                <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
                <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
                <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

                <script>
                    $(document).ready(function () {
                        $("#tema_id").select2();
                        jQuery('#coc_date').datepicker({
                            autoclose: true,
                            todayHighlight: true,
                            format: 'dd-mm-yyyy'
                        });
                        jQuery('#date-range').datepicker({
                            autoclose: true,
                            toggleActive: true,
                            todayHighlight: true,
                            format: 'dd-mm-yyyy'
                        });
                        $('.clockpicker').clockpicker({
                            donetext: 'Done'
                        });

                        $('.itemName').select2({
                            placeholder: 'Select pegawai',
                            ajax: {
                                url: '/coc/ajax-pemateri',
                                dataType: 'json',
                                delay: 250,
                                processResults: function (data) {
                                    return {
                                        results: $.map(data, function (item) {
                                            return {
                                                text: item.pernr + ' - ' + item.cname,
                                                id: item.pernr
                                            }
                                        })
                                    };
                                },
                                cache: true
                            }
                        });

                    });

                </script>
                <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
                <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    {!! $calendar->script() !!}


@stop
