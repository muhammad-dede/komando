@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Rekap Pelaporan CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/rekap-coc']) !!}
                <div class="form-group row">
                    {{--<label for="business_area" class="col-md-1 form-control-label">Unit</label>--}}
                    <div class="col-md-3">
                        @if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
                            {!!
                                Form::select(
                                    'company_code',
                                    $coCodeList,
                                    $cc_selected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'company_code',
                                        'required'
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
                                        'required',
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
                        <a href="{{url('report/rekap-coc/export/'.$cc_selected.'/'.$tgl_awal->format('d-m-Y').'/'.$tgl_akhir->format('d-m-Y'))}}" id="post" type="submit"
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
                                <th style="text-align: center" >LEVEL ORGANISASI UNIT</th>
                                <th style="text-align: center" >JABATAN (SEBAGAI PEMATERI)</th>
                                <th style="text-align: center" >JUMLAH PEJABAT</th>
                                <th style="text-align: center" >TARGET</th>
                                <th style="text-align: center" >TOTAL TARGET</th>
                                <th style="text-align: center" >REALISASI</th>
                                <th style="text-align: center" >PERSENTASE</th>
                            </tr>
                            </thead>


                            <tbody>
                            <?php
                            $x=1;
                            ?>
                            @foreach($jenjang_list as $jenjang)
                            <tr>
                                <td>Unit Level {{$jenjang->level}}</td>
                                <td><a href="{{url('report/rekap-coc/'.$cc_selected.'/'.$jenjang->id)}}">{{$jenjang->jenjang_jabatan}}</a></td>
                                <td align="center">{{$arr_jml[$x]}}</td>
                                <td align="center">{{$arr_target[$x]}}</td>
                                <?php
                                $total_target = $arr_jml[$x] * $arr_target[$x];
                                ?>
                                <td align="center">{{$total_target}}</td>
                                <td align="center">{{$arr_realisasi[$x]}}</td>
				@if($total_target==0)
				 <td align="center">0%</td>
				@else
                                <td align="center">{{number_format($arr_realisasi[$x]/$total_target*100,2)}}%</td>
				@endif
                            </tr>
                                <?php $x++?>
                            @endforeach
                            {{--@foreach($cc_selected->businessArea as $business_area)--}}
                                {{--<tr>--}}
                                    {{--<td>{{$x++}}</td>--}}
                                    {{--<td>{{$business_area->description}}</td>--}}
                                    {{--<td></td>--}}
                                    {{--<td></td>--}}
                                    {{--<td></td>--}}
                                    {{--<td></td>--}}
                                    {{--<td></td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}

                            {{--@if(isset($realisasi_list))--}}

                            {{--@foreach($realisasi_list as $realisasi)--}}
                                {{--<tr>--}}
                                    {{--<td>{{$x++}}</td>--}}
                                    {{--<td>{{$realisasi->coc->tema->tema}}</td>--}}
                                    {{--<td align="center">{{($realisasi->level!='')? 'Level '.$realisasi->level:''}}</td>--}}
                                    {{--<td>{{$realisasi->jenjangJabatan->jenjang_jabatan}}</td>--}}
                                    {{--<td><a href="{{url('coc/event/'.$realisasi->coc_id)}}">{{$realisasi->coc->judul}}</a></td>--}}
                                    {{--<td>--}}
                                        {{--{{$realisasi->leader->cname}}<br>--}}
                                        {{--<small class="text-muted">{{@$realisasi->leader->nip}} / {{@$realisasi->leader->strukturPosisi->stext}}</small>--}}
                                    {{--</td>--}}
                                    {{--<td>{{$realisasi->business_area}} - {{$realisasi->businessArea->description}}</td>--}}
                                    {{--<td>{{$realisasi->coc->tanggal_jam->format('Y-m-d')}}</td>--}}
                                    {{--<td>{{$realisasi->realisasi->format('Y-m-d')}}</td>--}}

                                {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--@endif--}}
                            {{--@foreach($pedoman_list as $pedoman)--}}
                            {{--<tr>--}}
                            {{--<td>{{$pedoman->nomor_urut}}</td>--}}
                            {{--<td><a href="{{url('master-data/pedoman-perilaku/'.$pedoman->id.'/display')}}">{{$pedoman->pedoman_perilaku}}</a></td>--}}
                            {{--<td align="center">{{$pedoman->pertanyaan()->where('status','ACTV')->count()}}</td>--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}

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
