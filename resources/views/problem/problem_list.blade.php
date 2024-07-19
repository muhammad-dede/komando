@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Problem</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <div class="row m-b-15">
                    <div class="col-md-12">
                        {{--<a href="{{url('report/problem/create')}}"--}}
                           {{--class="btn btn-danger waves-effect waves-light"><i--}}
                                    {{--class="fa fa-exclamation-triangle"></i> Report Problem</a>--}}

                        <a href="{{url('report/problem/create')}}"
                           class="btn btn-danger-outline waves-effect waves-light">
                        <span class="btn-label">
                            <i class="fa fa-exclamation-triangle"></i>
                        </span>
                            Send Error Report
                        </a>

                        <a href="{{asset('assets/doc/report_problem.pdf')}}"
                           class="btn btn-info-outline waves-effect waves-light" target="blank" style="margin-left: 20px;">
                        <span class="btn-label">
                            <i class="fa fa-info-circle"></i>
                        </span>
                            Panduan Error Reporting
                        </a>
                    </div>
                </div>
                {!! Form::open(['url'=>'report/problem', 'method' => 'GET']) !!}
                <div class="form-group row">
                    {{--<label for="business_area" class="col-md-1 form-control-label">Unit</label>--}}
                    <div class="col-md-4">
                        @if($user->hasRole('root'))
                            {!!
                                Form::select(
                                    'company_code',
                                    $ccList,
                                    $cc_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'company_code'
                                    ]
                                )
                            !!}
                        @else
                            {!!
                                Form::select(
                                    'company_code',
                                    $ccList,
                                    $cc_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'company_code',
                                    ]
                                )
                            !!}
                        @endif
                    </div>
                    {{--<div class="col-md-4">--}}
                        {{--@if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_ki'))--}}
                            {{--{!! Form::select('business_area', $bsAreaList, @$ba_selected,--}}
                                {{--['class'=>'form-control select2',--}}
                                {{--'id'=>'business_area']) !!}--}}
                        {{--@else--}}
                            {{--{!! Form::select('_business_area', $bsAreaList, @$ba_selected,--}}
                                {{--['class'=>'form-control select2',--}}
                                {{--'id'=>'business_area', 'disabled']) !!}--}}
                            {{--{!! Form::hidden('business_area', @$ba_selected) !!}--}}
                        {{--@endif--}}
                    {{--</div>--}}
                    {{--<div class="col-md-2">--}}
                        {{--<select name="tahun" class="form-control select2" id="tahun">--}}
                            {{--@for($x=2017;$x<=date('Y');$x++)--}}
                                {{--<option value="{{$x}}" {{($x==$tahun)?'selected':''}}>{{$x}}</option>--}}
                            {{--@endfor--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    <div class="col-md-6 button-list">
                        {{--@if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_ki'))--}}
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        {{--@endif--}}
                        {{--<a href="{{url('report/commitment/export/'.$ba_selected.'/'.$tahun)}}" id="post" type="submit"--}}
                           {{--class="btn btn-success waves-effect waves-light">--}}
                            {{--<i class="fa fa-file-excel-o"></i> &nbsp;Export</a>--}}
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row text-xs-center m-t-10">
                            <div class="col-xs-3">
                                <h3 data-plugin="counterup" class="text-danger">{{number_format($arr_jml[0])}}</h3>
                                <p class="text-muted">On Progress</p>
                            </div>
                            <div class="col-xs-3">
                                <h3 data-plugin="counterup" class="text-warning">{{number_format($arr_jml[1])}}</h3>
                                <p class="text-muted">Waiting for user</p>
                            </div>
                            <div class="col-xs-3">
                                <h3 data-plugin="counterup" class="text-primary">{{number_format($arr_jml[2])}}</h3>
                                <p class="text-muted">Resolved</p>
                            </div>
                            <div class="col-xs-3">
                                <h3 data-plugin="counterup" class="text-success">{{number_format($arr_jml[3])}}</h3>
                                <p class="text-muted">Closed</p>
                            </div>
                        </div>

                    </div>
                </div>
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

                <div class="row m-t-10">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="50" style="text-align: center">ID</th>
                                <th style="text-align: center">Tanggal, Jam</th>
                                <th style="text-align: center">Case Owner</th>
                                <th style="text-align: center">Unit</th>
                                <th style="text-align: center">Server</th>
                                <th style="text-align: center">Grup</th>
                                <th style="text-align: center">Deskripsi</th>
                                <th style="text-align: center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
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

    <script type="text/javascript">
        $(document).ready(function () {
            $("#company_code").select2();
//            $("#tahun").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: {!! json_encode($datatable->getColumns()) !!},
                "order": [[ 0, "desc" ]]
            });
        });

    </script>

@stop
