@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">

@stop

@section('title')
    <h4 class="page-title">Daftar CoC Administrator</h4>
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
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'coc/list/admin']) !!}
                <div class="form-group row">
                    <div class="col-md-3">

                        @if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('admin_ki') || Auth::user()->hasRole('root'))
                            {!!
                                Form::select(
                                    'business_area',
                                    $bsAreaList,
                                    $ba_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'business_area',
                                    ]
                                )
                            !!}
                        @else
                            {!!
                                Form::select(
                                    'business_area',
                                    $bsAreaList,
                                    $ba_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'business_area',
                                    ]
                                )
                            !!}
                        @endif

                    </div>
                    <div class="col-md-3">
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" name="start_date" placeholder="dd-mm-yyyy" value="{{$tgl_awal->format('d-m-Y')}}"/>
                            <span class="input-group-addon bg-custom b-0">to</span>
                            <input type="text" class="form-control" name="end_date" placeholder="dd-mm-yyyy" value="{{$tgl_akhir->format('d-m-Y')}}"/>
                        </div>
                    </div>

                    <div class="col-md-6 button-list">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                {!! Form::close() !!}
                
                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="50" style="text-align: center">No.</th>
                                <th style="text-align: center">Judul CoC</th>
                                <th style="text-align: center">Tema</th>
                                <th style="text-align: center">Jenis</th>
                                <th style="text-align: center">CoC Leader</th>
                                <th style="text-align: center">Unit/Bidang</th>
                                <th style="text-align: center">Lokasi</th>
                                <th style="text-align: center">Tanggal Jam</th>
                                <th style="text-align: center">Peserta</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>


                            <tbody>
                            @if(isset($coc_list))
                                <?php
                                    $x=1;
                                ?>
                                @foreach($coc_list as $coc)
                                    <tr>
                                        <td>{{$x++}}</td>
                                        <td>
                                            <a href="{{url('coc/event/'.$coc->id)}}">
                                                {{$coc->judul}}
                                            </a>
                                        </td>
                                        <td>{{$coc->tema->tema}}</td>
                                        <td>{{$coc->jenis->jenis}}</td>
                                        <td>
                                            @if($coc->realisasi!=null)
                                                {{@$coc->realisasi->leader->name}}
                                                <br><small class="text-muted">{{@$coc->realisasi->leader->nip}} - {{@$coc->realisasi->leader->jabatan}}</small>
                                            @else
                                                {{@$coc->leader->name}}
                                                <br><small class="text-muted">{{@$coc->leader->nip}} - {{@$coc->leader->jabatan}}</small>
                                            @endif
                                        </td>
                                        <td>{{@$coc->organisasi->stext}}</td>
                                        <td>{{@$coc->lokasi}}</td>
                                        <td>
                                            @if(@$coc->realisasi!=null)
                                                {{@$coc->realisasi->realisasi->format('Y-m-d H:i')}}
                                            @else
                                                {{@$coc->tanggal_jam->format('Y-m-d H:i')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($coc->jml_peserta!=0)
                                                {{$coc->attendants->count()}}/{{$coc->jml_peserta - $coc->jml_peserta_dispensasi}} ({{number_format(($coc->attendants->count()/($coc->jml_peserta - $coc->jml_peserta_dispensasi))*100, 2)}}%)
                                            @else
                                                {{$coc->attendants->count()}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($coc->status=='OPEN')
                                                <span class="label label-success">{{$coc->status}}</span>
                                            @elseif($coc->status=='CANC')
                                                <span class="label label-danger">{{$coc->status}}</span>
                                            @else
                                                <span class="label label-primary">{{$coc->status}}</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if(!($coc->checkAtendant(Auth::user()->id) || $coc->status=='COMP' || $coc->tanggal < \Carbon\Carbon::now()))
                                                <a href="{{url('coc/check-in/'.$coc->id)}}"
                                                   class="btn btn-success btn-xs waves-effect waves-light" title="Check-In">
                                                    <i class="fa fa-check-circle"></i>
                                                    </a>
                                            @endif
                                            @if($coc->status=='OPEN' && (( Auth::user()->can('input_coc_local') && $coc->admin_id == Auth::user()->id) || Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_ki')))
                                                {!! Form::open(['url'=>'']) !!}
                                                <a href="javascript:" id="post" type="submit"
                                                   class="btn btn-success btn-xs waves-effect waves-light"
                                                   data-toggle="modal"
                                                   data-target="#completeModal" title="Complete" onclick="javascript:ajaxComplete('{{$coc->id}}')">
                                                    <i class="fa fa-flag-checkered"></i>
                                                </a>
                                                <a href="javascript:" type="submit"
                                                   class="btn btn-danger btn-xs waves-effect waves-light"
                                                   title="Cancel"
                                                   onclick="javascript:cancelCoc('{{$coc->id}}')"
                                                        >
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            @else
                                                <a href="javascript:" id="post" type="submit"
                                                   class="btn btn-success btn-xs waves-effect waves-light disabled"
                                                   data-toggle="modal"
                                                   data-target="#completeModal" title="Complete">
                                                    <i class="fa fa-flag-checkered"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <!-- end row -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="completeModal" class="modal fade" role="dialog" aria-labelledby="completeModalLabel"
         aria-hidden="true">
        {!! Form::open(['url'=>'coc/complete', 'id'=>'form_complete']) !!}
        {!! Form::hidden('coc_id', '', ['id'=>'coc_id']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Realisasi CoC</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pemateri_id" class="form-control-label">Judul CoC</label>

                        <div>
                            <input type="text" class="form-control" placeholder="Judul CoC" id="judul_coc" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pemateri_id" class="form-control-label">Pembawa Materi / CoC Leader <span class="text-danger">*</span></label>

                        <div>
                            <select class="itemName form-control" name="nip_leader"
                                    style="width: 100% !important; padding: 0; z-index:10000;"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coc_date" class="form-control-label">Realisasi <span class="text-danger">*</span></label>

                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="complete_date"
                                   name="tanggal_coc" value="{{date('d-m-Y')}}">
                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jam_coc" class="form-control-label">Jam <span class="text-danger">*</span></label>
                        <div class="input-group clockpicker" data-placement="top" data-align="top"
                             data-autoclose="true">
                            <input type="text" class="form-control form-control-danger" placeholder="Jam" id="jam_coc" name="jam_coc"
                                   value="{{old('jam')}}">
                            <span class="input-group-addon"> <span class="zmdi zmdi-time"></span> </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jam_coc" class="form-control-label">Jumlah Dispensasi Peserta CoC (Sakit/Ijin/Cuti) <span class="text-danger">*</span></label>
                        <div>
                            <input type="text" class="form-control" placeholder="Jumlah" id="jml_peserta_dispensasi" name="jml_peserta_dispensasi" value="0">
                            <small class="text-muted">Jumlah ini akan mengurangi jumlah pegawai untuk perhitungan persentase kehadiran. <br>Persentase Kehadiran CoC = Jumlah Check-in / (Jumlah Pegawai - Jumlah Dispensasi)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Submit
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
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>


    <script type="text/javascript">
        $(document).ready(function () {
            $("#business_area").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable();

            $('.itemName').select2({
                placeholder: 'Select Pegawai',
                ajax: {
                    url: '/coc/ajax-leader',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nip+' - '+item.name,
                                    id: item.nip
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
        jQuery('#coc_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
        jQuery('#complete_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
        $('.clockpicker').clockpicker({
            donetext: 'Done'
        });
        jQuery('#date-range').datepicker({
            autoclose: true,
            toggleActive: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });

        function ajaxComplete(coc_id){
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-coc/')}}'+'/'+coc_id,
                success:function(data){
                    $('#coc_id').val(coc_id);
                    $('#judul_coc').val(data.judul);
                    var arr = data.tanggal_jam.split(' ');
                    var tgl = arr[0].split('-');
                    var jam = arr[1].split(':');
                    $('#complete_date').val(tgl[2]+'-'+tgl[1]+'-'+tgl[0]);
                    $('#jam_coc').val(jam[0]+':'+jam[1]);
                    $('#jam_coc').val(jam[0]+':'+jam[1]);
                }
            });
        }

        //Warning Message
        function cancelCoc(coc_id) {
            swal({
                title: "Are you sure?",
                text: "Jadwal CoC dibatalkan dan tidak dapat digunakan kembali.",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, cancel it!",
                closeOnConfirm: false
            }, function () {
                window.location.href = '{{url('/coc/cancel')}}/'+coc_id;
            });
        }

    </script>

@stop