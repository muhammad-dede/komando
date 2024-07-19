@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Target Briefing dan Internalisasi CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'master-data/target-coc']) !!}
                <div class="form-group row">
                    {{--<label for="business_area" class="col-md-1 form-control-label">Unit</label>--}}
                    <div class="col-md-2">
                        {!! Form::select('tahun', $tahunList, $tahun_selected,
                            ['class'=>'form-control select2',
                            'id'=>'tahun']) !!}

                    </div>
                    <div class="col-md-8 button-list">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        {{--<a href="{{url('report/briefing-coc/export/'.$cc_selected)}}" id="post" type="submit"--}}
                        {{--class="btn btn-success waves-effect waves-light">--}}
                        {{--<i class="fa fa-file-excel-o"></i> &nbsp;Export</a>--}}
                    </div>
                </div>
                {!! Form::close() !!}

                {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                {{--<a href="{{url('report/history-coc/export')}}" id="post" type="submit"--}}
                {{--class="btn btn-success w-lg waves-effect waves-light">--}}
                {{--<i class="fa fa-file-excel-o"></i> &nbsp;Export</a>--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable_" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="100" style="text-align: center" rowspan="2">Level Organisasi Unit</th>
                                <th width="200" style="text-align: center" rowspan="2">Jabatan</th>
                                <th style="text-align: center" colspan="3">TW I</th>
                                <th style="text-align: center" colspan="3">TW II</th>
                                <th style="text-align: center" colspan="3">TW III</th>
                                <th style="text-align: center" colspan="3">TW IV</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">Cluster 1</th>
                                <th style="text-align: center">Cluster 2</th>
                                <th style="text-align: center">Cluster 3</th>
                                <th style="text-align: center">Cluster 1</th>
                                <th style="text-align: center">Cluster 2</th>
                                <th style="text-align: center">Cluster 3</th>
                                <th style="text-align: center">Cluster 1</th>
                                <th style="text-align: center">Cluster 2</th>
                                <th style="text-align: center">Cluster 3</th>
                                <th style="text-align: center">Cluster 1</th>
                                <th style="text-align: center">Cluster 2</th>
                                <th style="text-align: center">Cluster 3</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($jenjang_list as $jenjang)
                                    <?php
//                                    $target = $jenjang->targetCoCTahun($tahun_selected);
                                    $cluster1 = $jenjang->targetCoC()->where('tahun', $tahun_selected)->where('cluster', 1)->first();
                                    $cluster2 = $jenjang->targetCoC()->where('tahun', $tahun_selected)->where('cluster', 2)->first();
                                    $cluster3 = $jenjang->targetCoC()->where('tahun', $tahun_selected)->where('cluster', 3)->first();
                                    ?>
                                    <tr>
                                        <td>Unit Level {{$jenjang->level}}</td>
                                        <td>
                                            <a href="{{url('master-data/target-coc/'.$tahun_selected.'/'.$jenjang->id.'/edit')}}">
                                                {{$jenjang->jenjang_jabatan}} <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                        <td align="center">{{@$cluster1->tw1}}</td>
                                        <td align="center">{{@$cluster2->tw1}}</td>
                                        <td align="center">{{@$cluster3->tw1}}</td>

                                        <td align="center">{{@$cluster1->tw2}}</td>
                                        <td align="center">{{@$cluster2->tw2}}</td>
                                        <td align="center">{{@$cluster3->tw2}}</td>

                                        <td align="center">{{@$cluster1->tw3}}</td>
                                        <td align="center">{{@$cluster2->tw3}}</td>
                                        <td align="center">{{@$cluster3->tw3}}</td>

                                        <td align="center">{{@$cluster1->tw4}}</td>
                                        <td align="center">{{@$cluster2->tw4}}</td>
                                        <td align="center">{{@$cluster3->tw4}}</td>
                                    </tr>
                                @endforeach
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
            $("#tahun").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable();
        });

    </script>

@stop