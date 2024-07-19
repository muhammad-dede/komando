@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Persentase CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/persentase-coc']) !!}
                    <div class="form-group row">
                        <div class="col-md-3">
                            @if($user->hasRole('admin_pusat') || $user->hasRole('root'))
                                {!!
                                    Form::select(
                                        'company_code',
                                        $coCodeList,
                                        request('company_code'),
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
                                        request('company_code'),
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
                        <div class="col-md-1">
                            <select name="jenis_coc_id" class="form-control">
                                <option value="1" {{($jenis_coc_id=='1')?'selected':''}}>Nasional</option>
                                <option value="2" {{($jenis_coc_id=='2')?'selected':''}}>GM</option>
                                <option value="5" {{($jenis_coc_id=='5')?'selected':''}}>Local</option>
                            </select>
                        </div>
                        <div class="col-md-5 button-list">
                            <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                            <a
                                href="{{ url('report/persentase-coc/export/' . $cc_selected . '/' . $startDate . '/' . $endDate . '/' . $jenis_coc_id) }}"
                                id="post"
                                type="submit"
                                class="btn btn-success waves-effect waves-light"
                            >
                                <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                        </div>
                    </div>
                {!! Form::close() !!}

                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center" >NIP</th>
                                    <th style="text-align: center" >ADMIN</th>
                                    <th style="text-align: center" >UNIT</th>
                                    @if ($cc_selected == '1000')
                                        <th style="text-align: center" >DIVISI</th>
                                    @endif
                                    <th style="text-align: center" >JML COC OPEN</th>
                                    <th style="text-align: center" >JML COC COMP</th>
                                    <th style="text-align: center" >JML RENCANA PESERTA</th>
                                    <th style="text-align: center" >JML DISPENSASI PESERTA</th>
                                    <th style="text-align: center" >JML PESERTA</th>
                                    <th style="text-align: center" >JML CHECK IN</th>
                                    <th style="text-align: center" >% CHECK IN</th>
                                    <th style="text-align: center" >JUMLAH BACA MATERI</th>
                                    <th style="text-align: center" >% BACA MATERI</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $x=1;
                                    $total_coc_open = 0;
                                    $total_coc = 0;
                                    $total_peserta = 0;
                                    $total_peserta_dispensasi = 0;
                                    $total_peserta_real = 0;
                                    $total_checkin = 0;
                                    $total_baca = 0;
                                @endphp

                                @foreach($users as $user)
                                    @php
                                        $jml_coc_open = 0;
                                        $jml_coc = 0;
                                        $jml_peserta = 0;
                                        $jml_peserta_dispensasi = 0;

                                        if ($user->cocOpen) {
                                            $jml_coc_open = $user->cocOpen->count();
                                        }

                                        if ($user->cocComp) {
                                            $jml_coc = $user->cocComp->count();
                                            $jml_peserta = $user->cocComp->sum('jml_peserta');
                                            $jml_peserta_dispensasi = $user->cocComp->sum('jml_peserta_dispensasi');
                                        }

                                        $jml_peserta_real = $jml_peserta - $jml_peserta_dispensasi;
                                        $jml_checkin = $user->jml_checkin;
                                        $jml_baca = $user->jml_baca;
                                    @endphp
                                    <tr>
                                        <td>{{ $user->nip }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->business_area . ' - ' . $user->businessArea->description }}</td>
                                        @if ($cc_selected == '1000')
                                            <td>{{$user->getDivisi()}}</td>
                                        @endif
                                        <td align="center">{{ $jml_coc_open }}</td>
                                        <td align="center">{{ $jml_coc }}</td>
                                        <td align="center">{{ $jml_peserta }}</td>
                                        <td align="center">{{ $jml_peserta_dispensasi }}</td>
                                        <td align="center">{{ $jml_peserta_real }}</td>
                                        <td align="center">{{ $jml_checkin }}</td>
                                        <td align="center">
                                            @if ($jml_peserta_real!=0)
                                                {{ number_format($jml_checkin / $jml_peserta_real * 100, 2) }}%
                                            @else
                                                0%
                                            @endif
                                        </td>
                                        <td align="center">{{ $jml_baca }}</td>
                                        <td align="center">
                                            @if ($jml_peserta_real!=0)
                                                {{ number_format($jml_baca / $jml_peserta_real * 100, 2) }}%
                                            @else
                                                0%
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $x++;
                                        $total_coc_open = $total_coc_open + $jml_coc_open;
                                        $total_coc = $total_coc + $jml_coc;
                                        $total_peserta = $total_peserta + $jml_peserta;
                                        $total_peserta_dispensasi = $total_peserta_dispensasi + $jml_peserta_dispensasi;
                                        $total_peserta_real = $total_peserta_real + $jml_peserta_real;
                                        $total_checkin = $total_checkin + $jml_checkin;
                                        $total_baca = $total_baca + $jml_baca;
                                    @endphp
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th style="text-align: center"></th>
                                    <th style="text-align: center"></th>
                                    @if ($cc_selected == '1000')
                                        <th style="text-align: center"></th>
                                    @endif
                                    <th style="text-align: center">TOTAL</th>
                                    <th style="text-align: center">{{ number_format($total_coc_open) }}</th>
                                    <th style="text-align: center">{{ number_format($total_coc) }}</th>
                                    <th style="text-align: center">{{ number_format($total_peserta) }}</th>
                                    <th style="text-align: center">{{ number_format($total_peserta_dispensasi) }}</th>
                                    <th style="text-align: center">{{ number_format($total_peserta_real) }}</th>
                                    <th style="text-align: center">{{ number_format($total_checkin) }}</th>
                                    <th style="text-align: center">
                                        @if ($total_peserta_real!=0)
                                            {{ number_format($total_checkin / $total_peserta_real * 100, 2) }}%
                                        @else
                                            0%
                                        @endif
                                    </th>
                                    <th style="text-align: center">{{ $total_baca }}</th>
                                    <th style="text-align: center">
                                        @if ($total_peserta_real!=0)
                                            {{ number_format($total_baca / $total_peserta_real * 100, 2) }}%
                                        @else
                                            0%
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
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
                    header: true,
                    footer: true
                }
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
