@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Jumlah CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/jumlah-coc', 'method' => 'GET']) !!}
                <div class="form-group row">
                    <div class="col-md-3">
                        @if($user->hasRole('admin_pusat') || $user->hasRole('root'))
                            {!!
                                Form::select(
                                    'company_code',
                                    $coCodeList,
                                    $cc_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'company_code',
                                    ]
                                )
                            !!}
                        @else
                            {!!
                                Form::select(
                                    'company_code',
                                    $coCodeList,
                                    $cc_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'company_code',
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
                        <a href="{{url('report/jumlah-coc/export/' . $cc_selected . '/'.$tgl_awal->format('d-m-Y').'/'.$tgl_akhir->format('d-m-Y'))}}" id="post" type="submit"
                           class="btn btn-success waves-effect waves-light">
                            <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                    </div>
                </div>
                {!! Form::close() !!}

                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center" >UNIT</th>
                                    <th style="text-align: center" >JUMLAH COC CANCEL</th>
                                    <th style="text-align: center" >JUMLAH COC OPEN</th>
                                    <th style="text-align: center" >JUMLAH COC COMPLETE</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                            <tfoot>
                                <tr>
                                    <th style="text-align: center" >TOTAL</th>
                                    <th style="text-align: center" >{{$total_coc_cancel}}</th>
                                    <th style="text-align: center" >{{$total_coc_open}}</th>
                                    <th style="text-align: center" >{{$total_coc_comp}}</th>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- end row -->
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#company_code").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable({
                fixedHeader: {
                    header: false,
                    footer: true
                },
                processing: true,
                serverSide: true,
                @if(request()->getQueryString())
                ajax: window.location.href,
                @else
                ajax: window.location.href + "?company_code={{ $cc_selected }}",
                @endif
                columns: {!! json_encode($datatable->getColumns()) !!}
            });
        });
        jQuery('#date-range').datepicker({
            autoclose: true,
            toggleActive: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });

    </script>

@stop
