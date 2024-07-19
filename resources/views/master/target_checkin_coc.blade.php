@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        #datatable td:not(:first-child) {
            text-align: center;
        }
    </style>  
@stop

@section('title')
    <h4 class="page-title">Target Persentase Check-in CoC</h4>
@stop

@section('content')
    {{-- <div class="row"> --}}
        {{-- <div class="col-sm-12"> --}}
            <div class="card-box table-responsive">
                {{-- {!! Form::open(['url'=>'master-data/target-coc']) !!}
                <div class="form-group row">
                    <div class="col-md-2">
                        {!! Form::select('tahun', $tahunList, $tahun_selected,
                            ['class'=>'form-control select2',
                            'id'=>'tahun']) !!}

                    </div>
                    <div class="col-md-8 button-list">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                {!! Form::close() !!} --}}
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{url('master-data/target-checkin-coc/create')}}" class="btn btn-primary">
                            <i class="fa fa-plus "></i> New Target
                        </a>
                    </div>
                </div>
                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th style="text-align: center">Unit / Divisi</th>
                                <th style="text-align: center">Kode Organisasi</th>
                                <th style="text-align: center">Company Code</th>
                                <th style="text-align: center">Target</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($unit_monitoring_list as $unit)
                                    <tr>
                                        <td>{{$unit->nama_unit}}</td>
                                        <td>{{$unit->orgeh}}</td>
                                        <td>{{$unit->company_code}}</td>
                                        <td>{{$unit->target_realisasi_coc}}%</td>
                                        <td>
                                            {{-- Button Edit --}}
                                            <a href="{{url('master-data/target-checkin-coc/'.$unit->id.'/edit')}}" class="btn btn-primary">
                                                <i class="fa fa-pencil"></i> Edit
                                            </a>
                                            {{-- Button Delete --}}
                                            <a href="{{url('master-data/target-checkin-coc/'.$unit->id.'/delete')}}" class="btn btn-danger" onclick="javascript:if(confirm('Apakah yakin ingin menghapus?')){return true;}else{return false;}">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- end row -->
                    </div>
                </div>

            </div>
        {{-- </div> --}}
    {{-- </div> --}}
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#tahun").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable();
        });

    </script>

@stop