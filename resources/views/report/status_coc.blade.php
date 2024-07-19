@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">

@stop

@section('title')
    <h4 class="page-title">Status CoC Business Area</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/status-coc', 'method' => 'GET']) !!}
                <div class="form-group row">
                    {{--<label for="business_area" class="col-md-1 form-control-label">Unit</label>--}}
                    <div class="col-md-3">
                        {{--{!! Form::select('business_area', $bsAreaList, $ba_selected,--}}
                            {{--['class'=>'form-control select2',--}}
                            {{--'id'=>'business_area']) !!}--}}

                        @if($user->hasRole('admin_pusat') || $user->hasRole('root') || Auth::user()->hasRole('admin_ki'))
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
                    <div class="col-md-2">
                        <select name="status_coc" class="form-control">
                            <option value="OPEN" {{($status_coc=='OPEN')?'selected':''}}>OPEN</option>
                            <option value="COMP" {{($status_coc=='COMP')?'selected':''}}>COMPLETE</option>
                            <option value="CANC" {{($status_coc=='CANC')?'selected':''}}>CANCEL</option>
                        </select>
                    </div>
                    {{--<div class="col-md-2">--}}
                        {{--<div class="input-group">--}}
                            {{--{!! Form::text('coc_date', $tgl_selected, ['class'=>'form-control', 'placeholder'=>'dd-mm-yyyy', 'id'=>'coc_date']) !!}--}}
                            {{--<input type="text" class="form-control" placeholder="dd-mm-yyyy" id="coc_date" name="coc_date">--}}
                            {{--<span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    <div class="col-md-4 button-list">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a href="{{url('report/status-coc/export/' . $ba_selected . '/'.$tgl_awal->format('d-m-Y').'/'.$tgl_akhir->format('d-m-Y').'/'.$status_coc)}}" id="post" type="submit"
                           class="btn btn-success waves-effect waves-light">
                            <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                    </div>
                </div>
                {!! Form::close() !!}
                {{--<h4 class="m-t-0 header-title"><b>Default Example</b></h4>--}}

                {{--<p class="text-muted font-13 m-b-30">--}}
                {{--DataTables has most features enabled by default, so all you need to do to use it with--}}
                {{--your own tables is to call the construction function: <code>$().DataTable();</code>.--}}
                {{--</p>--}}

                {{--<div class="row">--}}
                    {{--<div class="col-md-12">--}}
                        {{--<a href="{{url('report/history-coc/export')}}" id="post" type="submit"--}}
                           {{--class="btn btn-success w-lg waves-effect waves-light">--}}
                            {{--<i class="fa fa-file-excel-o"></i> &nbsp;Export</a>--}}
                    {{--</div>--}}
                {{--</div>--}}

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
                                <th style="text-align: center">% Peserta</th>
                                <th style="text-align: center">Admin CoC</th>
                                <th style="text-align: center">Status</th>
                                {{--<th style="text-align: center">Action</th>--}}
                            </tr>
                            </thead>
                        </table>
                        <!-- end row -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- sample modal content -->
    <div id="completeModal" class="modal fade" role="dialog" aria-labelledby="completeModalLabel"
         aria-hidden="true">
{{--        {!! Form::open(['url'=>'coc/complete/'.$coc->id, 'id'=>'form_complete']) !!}--}}
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
                        <label for="pemateri_id" class="form-control-label">Pembawa Materi / CoC Leader</label>

                        <div>
                            <select class="itemName form-control" name="pernr_leader"
                                    style="width: 100% !important; padding: 0; z-index:10000;"></select>
                        </div>
                    </div>
                    {{--<div class="form-group">
                        <label for="jml_peserta" class="form-control-label">Jumlah Peserta</label>

                        <div>
                            <input type="text" class="form-control" placeholder="Jumlah peserta" id="jml_peserta" name="jml_peserta">
                        </div>
                    </div>--}}
                    <div class="form-group">
                        <label for="coc_date" class="form-control-label">Realisasi</label>

                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="complete_date"
                                   name="tanggal_coc" value="{{date('d-m-Y')}}">
                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jam_coc" class="form-control-label">Jam</label>
                        <div class="input-group clockpicker" data-placement="top" data-align="top"
                             data-autoclose="true">
                            <input type="text" class="form-control form-control-danger" placeholder="Jam" id="jam_coc" name="jam_coc"
                                   value="{{old('jam')}}">
                            <span class="input-group-addon"> <span class="zmdi zmdi-time"></span> </span>
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
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                bPaginate: true,
                ajax: {
                    url: "{{ route('data_table.report.status.coc') }}",
                    data: function (d) {
                        d.start_date = $('input[name=start_date]').val();
                        d.end_date = $('input[name=end_date]').val();
                        d.business_area = $('#business_area').val();
                        d.status_coc = $('select[name=status_coc]').val();
                    },
                    dataType: "json",
                    type: "GET"
                },
                columns: [
                    {"data":"no"},
                    {"data":"judul_coc"},
                    {"data":"tema"},
                    {"data":"jenis"},
                    {"data":"coc_leader"},
                    {"data":"unit_bidang"},
                    {"data":"lokasi"},
                    {"data":"tanggal_jam"},
                    {"data":"peserta"},
                    {"data":"persentase_peserta"},
                    {"data":"admin_coc"},
                    {"data":"status"}
                ]
            });

            $('.itemName').select2({
                placeholder: 'Select Pegawai',
                ajax: {
                    url: '/coc/ajax-pemateri',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.pa0032.nip+' - '+item.sname,
                                    id: item.pernr
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
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
//                    console.log(data.judul);
                    $('#coc_id').val(coc_id);
//                    $('#judul_coc').val(coc_id+' - '+data.judul);
                    $('#judul_coc').val(data.judul);
                    var arr = data.tanggal_jam.split(' ');
                    var tgl = arr[0].split('-');
                    var jam = arr[1].split(':');
                    $('#complete_date').val(tgl[2]+'-'+tgl[1]+'-'+tgl[0]);
                    $('#jam_coc').val(jam[0]+':'+jam[1]);
                    $('#jam_coc').val(jam[0]+':'+jam[1]);
//                    $("#do_list").html(data);
                }
            });
        }

        //Warning Message
        function cancelCoc(coc_id) {
//            var coc = coc_id;
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
//                alert('id: '+coc);
                window.location.href = '{{url('/coc/cancel')}}/'+coc_id;
//                swal("Canceled!", "Jadwal CoC berhasil dibatalkan.", "success");
            });
        }

    </script>

@stop
