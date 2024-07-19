@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Materi CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">

                {!! Form::open(['url'=>'master-data/materi', 'method' => 'GET']) !!}
                <div class="form-group row">
                    <div class="col-md-2">
                        <select name="tahun" class="form-control select2" id="tahun">
                            @for($x=2017;$x<=date('Y');$x++)
                                <option value="{{$x}}" {{($x==$tahun)?'selected':''}}>{{$x}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="jenis_materi_id" class="form-control select2" id="jenis_materi_id">
                            <option value="1" {{($jenis_materi_id==1)?'selected':''}}>Nasional</option>
                            <option value="2" {{($jenis_materi_id==2)?'selected':''}}>GM</option>
                        </select>
                    </div>
                    <div class="col-md-6 button-list">
                        {{--@if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_ki'))--}}
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        {{--@endif--}}
                        <a href="{{url('master-data/materi/export/'.$tahun.'/'.$jenis_materi_id)}}" id="post" type="submit"
                           class="btn btn-success waves-effect waves-light">
                            <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                    </div>
                </div>
                {!! Form::close() !!}

                {{--<div class="row m-b-20">--}}
                    {{--<div class="col-xl-12">--}}
                        {{--<div class="button-list">--}}
                            {{--<a href="{{url('master-data/materi/create/')}}"--}}
                               {{--class="btn btn-primary-outline btn-rounded waves-effect waves-light">--}}
                        {{--<span class="btn-label">--}}
                            {{--<i class="fa fa-plus"></i>--}}
                        {{--</span>--}}
                                {{--Create New--}}
                            {{--</a>--}}

                        {{--</div>--}}

                    {{--</div>--}}
                {{--</div>--}}
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th style="text-align: center">Judul</th>
                        <th style="text-align: center">Penulis</th>
                        <th style="text-align: center">Tema</th>
                        <th style="text-align: center">Jenis Materi</th>
                        <th style="text-align: center">Cluster Materi</th>
                        <th style="text-align: center">Company Code</th>
                        <th style="text-align: center">Business Area</th>
                        <th style="text-align: center">Organisasi</th>
                        <th style="text-align: center"><i class="zmdi zmdi-thumb-up" title="Like"></i></th>
                        <th style="text-align: center"><i class="zmdi zmdi-thumb-down" title="Dislike"></i></th>
                        <th style="text-align: center">5 <i class="zmdi zmdi-star" title="5 Star"></i></th>
                        <th style="text-align: center">4 <i class="zmdi zmdi-star" title="4 Star"></i></th>
                        <th style="text-align: center">3 <i class="zmdi zmdi-star" title="3 Star"></i></th>
                        <th style="text-align: center">2 <i class="zmdi zmdi-star" title="2 Star"></i></th>
                        <th style="text-align: center">1 <i class="zmdi zmdi-star" title="1 Star"></i></th>
                        <th style="text-align: center">Review</th>
                        <th style="text-align: center">Rate</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!-- end row -->

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
            $("#tahun").select2();
            $("#jenis_materi_id").select2();
            let t = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: {!! json_encode($datatable->getColumns()) !!}
            });
        });

    </script>

@stop
