@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Commitment Company Code</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/commitment-induk', 'method' => 'GET']) !!}
                <div class="form-group row">
                    {{--<label for="business_area" class="col-md-1 form-control-label">Unit</label>--}}
                    <div class="col-md-4">
                        @if($user->hasRole('root') || $user->hasRole('admin_ki'))
                            {!!
                                Form::select(
                                    'company_code',
                                    $coCodeList,
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
                                    $coCodeList,
                                    $cc_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'company_code'
                                    ]
                                )
                            !!}
                        @endif
                        {{--{!! Form::select('business_area', $bsAreaList, $ba_selected,--}}
                            {{--['class'=>'form-control select2',--}}
                            {{--'id'=>'business_area']) !!}--}}
                    </div>
                    {{--<div class="col-md-2">--}}
                        {{--<select name="tahun" class="form-control select2" id="tahun">--}}
                            {{--@for($x=2017;$x<=date('Y');$x++)--}}
                                {{--<option value="{{$x}}" {{($x==$tahun)?'selected':''}}>{{$x}}</option>--}}
                            {{--@endfor--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    <div class="col-md-6 button-list">
                        {{--@if($user->hasRole('root') || $user->hasRole('admin_ki'))--}}
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        {{--@endif--}}
                        <a href="{{url('report/commitment-induk/export/'.$cc_selected.'/'.$tahun)}}" id="post" type="submit"
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
                                <th style="text-align: center">Nama Pegawai</th>
                                <th style="text-align: center">NIP</th>
                                <th style="text-align: center">Jabatan</th>
                                <th style="text-align: center">Unit</th>
                                {{--<th style="text-align: center">Bidang</th>--}}
                                {{--<th style="text-align: center">Tahun</th>--}}
                                @for($y=$tahun-1;$y<=$tahun;$y++)
                                    <th style="text-align: center">Progress {{$y}}</th>
                                @endfor
                                <th style="text-align: center">Commitment</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
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
            $("#tahun").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: {!! json_encode($datatable->getColumns()) !!},
            });
        });

    </script>

@stop
