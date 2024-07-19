@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Rekap Pelaporan CoC - {{$jenjang->jenjang_jabatan}}</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/rekap-coc/'.$cc_selected->company_code.'/'.$jenjang->id]) !!}
                <div class="form-group row">
                    {{--<label for="business_area" class="col-md-1 form-control-label">Unit</label>--}}
                    <div class="col-md-3">
                        @if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
                            {!! Form::select('company_code', $coCodeList, $cc_selected->company_code,
                                ['class'=>'form-control select2',
                                'id'=>'company_code']) !!}
                        @else
                            {!! Form::select('_company_code', $coCodeList, $cc_selected->company_code,
                            ['class'=>'form-control select2',
                            'id'=>'company_code', 'disabled']) !!}
                            {!! Form::hidden('company_code', $cc_selected->company_code) !!}
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
                        <a href="{{url('report/rekap-coc/export-detil/'.$cc_selected->company_code.'/'.$jenjang->id.'/'.$tgl_awal->format('d-m-Y').'/'.$tgl_akhir->format('d-m-Y'))}}" id="post" type="submit"
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
                            {{--<tr>--}}
                                {{--<th width="50" style="text-align: center" rowspan="2">No.</th>--}}
                                {{--<th style="text-align: center" rowspan="2">Bidang/Unit</th>--}}
                                {{--<th style="text-align: center" rowspan="2">PIC Pelaporan CoC</th>--}}
                                {{--<th style="text-align: center" colspan="2">Target Pelaporan CoC</th>--}}
                                {{--<th style="text-align: center" colspan="2">Realisasi Pelaporan CoC</th>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<th style="text-align: center">Kali Pelaksanaan</th>--}}
                                {{--<th style="text-align: center">Pembawa Materi</th>--}}
                                {{--<th style="text-align: center">Kali Pelaksanaan</th>--}}
                                {{--<th style="text-align: center">Pembawa Materi</th>--}}
                            {{--</tr>--}}
                            <tr>
                                {{--<th style="text-align: center" width="10">NO</th>--}}
                                <th style="text-align: center" >PERNR</th>
                                <th style="text-align: center" >NIPEG</th>
                                <th style="text-align: center" >PEGAWAI</th>
                                <th style="text-align: center" >JABATAN (SEBAGAI PEMATERI)</th>
                                <th style="text-align: center" >UNIT</th>
                                <th style="text-align: center" >TARGET</th>
                                {{--<th style="text-align: center" >TOTAL TARGET</th>--}}
                                <th style="text-align: center" >REALISASI</th>
                                <th style="text-align: center" >PERSENTASE</th>
                            </tr>
                            </thead>


                            <tbody>
                            <?php
                            $x=1;
                            ?>
                            @foreach($pejabat_list as $pejabat)
                            <tr>
                                {{--<td>{{$x}}</td>--}}
                                <td>
                                    {{@$pejabat->pernr}}
                                </td>
                                <td>
                                    {{@$pejabat->jabatan->nip}}
                                </td>
                                <td>
                                    {{@$pejabat->jabatan->cname}}
                                </td>
                                <td>
                                    {{@$pejabat->jabatan->strukturPosisi->stext}}
                                </td>
                                <td>
                                    {{strtoupper(@$pejabat->gsber.' - '.@$pejabat->businessArea->description)}}
                                </td>
                                <td align="center">
                                    {{$target}}
                                </td>
                                <?php
                                $realisasi = App\RealisasiCoc::getRealisasiJabatan($pejabat->getPositionDefinitive(), $pejabat->gsber, $tgl_awal, $tgl_akhir);
                                //$realisasi = App\RealisasiCoc::getRealisasiPejabat($pejabat->pernr, $pejabat->gsber, $tgl_awal, $tgl_akhir);
                                ?>
                                {{--<td align="center">{{$total_target}}</td>--}}
                                <td align="center">
                                    {{$realisasi}}
                                </td>
                                <td align="center">
                                    {{number_format($realisasi/$target*100,2)}}%
                                </td>
                            </tr>
                                <?php $x++?>
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
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#company_code").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable();
        });
        jQuery('#date-range').datepicker({
            autoclose: true,
            toggleActive: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });

    </script>

@stop