@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">

@stop

@section('title')
    <h4 class="page-title">Daftar CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'coc/list']) !!}
                <div class="form-group row">
                    <div class="col-md-3">
                        @if($user->hasRole('root') || $user->hasRole('admin_pusat') || $user->hasRole('admin_ki'))
                            {!!
                                Form::select(
                                    'business_area',
                                    $businessAreaOpts,
                                    $businessAreaSelected,
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
                                    $businessAreaOpts,
                                    $businessAreaSelected,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'business_area',
                                    ]
                                )
                            !!}
                        @endif

                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            {!! Form::text('coc_date', $tgl_selected, ['class'=>'form-control', 'placeholder'=>'dd-mm-yyyy', 'id'=>'coc_date']) !!}
                            {{--<input type="text" class="form-control" placeholder="dd-mm-yyyy" id="coc_date" name="coc_date">--}}
                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                        </div>

                    </div>
                    <div class="col-md-7 button-list">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        {{--<a href="{{url('coc/list')}}" id="post" type="submit"--}}
                           {{--class="btn btn-success waves-effect waves-light">--}}
                            {{--<i class="fa fa-file-excel-o"></i> &nbsp;Export</a>--}}
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
                                <th style="text-align: center">Jam</th>
                                <th style="text-align: center">Peserta</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>


                            <tbody>
                            @if(isset($coc_list))
                                <?php
                                    $x=1;
//                                        $jml_perilaku = App\PedomanPerilaku::all()->count();
                                ?>
                                @foreach($coc_list as $coc)
                                    <tr>
                                        <td>{{$x++}}</td>
                                        <td>
                                            <a href="{{url('coc/event/'.$coc->id)}}">
                                                {{$coc->judul}}
                                            </a>
                                        </td>
                                        <td>{{@$coc->tema->tema}}</td>
                                        <td>{{@$coc->jenis->jenis}}</td>
                                        <td>
                                            @if($coc->realisasi!=null)
                                                {{@$coc->realisasi->leader->name}}
                                                <br><small class="text-muted">{{@$coc->realisasi->leader->nip}} - {{@$coc->realisasi->leader->jabatan}}</small>
                                            @else
                                                {{$coc->leader->name}}
                                                <br><small class="text-muted">{{@$coc->leader->nip}} - {{@$coc->leader->jabatan}}</small>
                                            @endif
                                        </td>
                                        <td>{{@$coc->organisasi->stext}}</td>
                                        <td>{{$coc->lokasi}}</td>
                                        <td>
                                            @if($coc->realisasi!=null)
                                                {{@$coc->realisasi->realisasi->format('H:i')}}
                                            @else
                                                {{@$coc->tanggal_jam->format('H:i')}}
                                            @endif
                                        </td>
                                        <td>{{$coc->attendants->count()}}/{{$coc->jml_peserta-$coc->jml_peserta_dispensasi}} ({{number_format(($coc->attendants->count()/($coc->jml_peserta-$coc->jml_peserta_dispensasi))*100, 2)}}%)</td>
                                        <td>
                                            @if($coc->status=='OPEN')
                                                <span class="label label-success">{{$coc->status}}</span>
                                            @elseif($coc->status=='CANC')
                                                <span class="label label-danger">{{$coc->status}}</span>
                                            @else
                                                <span class="label label-primary">{{$coc->status}}</span>
                                            @endif

                                            {{--@if($coc->tanggal < \Carbon\Carbon::now() && $coc->status=='OPEN')--}}
                                                    {{--<span class="label label-danger">EXP</span>--}}
                                            {{--@endif--}}
                                        </td>
                                        <td>
                                            {{-- @if((@$coc->materi->jenisMateri->jenis=='Nasional' && $coc->status!='COMP' && env('OPEN_COC_NAS',true)))
                                                <a href="{{url('coc/check-in/'.$coc->id)}}"
                                                   class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i>
                                                    Check-In</a>
                                            @else
                                                @if(env('OPEN_COC_NAS',true))
                                                    <a href="{{url('coc/check-in/'.$coc->id)}}"
                                                       class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i>
                                                        Check-In</a>
                                                @else
                                                    @if($coc->checkAtendant(Auth::user()->id) || $coc->status=='COMP' || $coc->tanggal < \Carbon\Carbon::now())
                                                        <a href="{{url('coc/check-in/'.$coc->id)}}"
                                                           class="btn btn-success btn-xs disabled"><i class="fa fa-check-circle"></i>
                                                            Check-In</a>
                                                    @else
                                                        <a href="{{url('coc/check-in/'.$coc->id)}}"
                                                           class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i>
                                                            Check-In</a>
                                                    @endif
                                                @endif
                                            @endif --}}

                                            <?php
                                            //dd($coc->tanggal_jam->format('Y-m-d') < \Carbon\Carbon::now()->format('Y-m-d'));
                                            //dd($coc->tanggal_jam->format('Ymd') > date('Ymd'));
                                            ?>

                                            @if($coc->checkAtendant(Auth::user()->id) || $coc->status=='COMP')
                                                <a href="{{url('coc/event/'.$coc->id)}}"
                                                    class="btn btn-primary btn-xs" title=""><i class="fa fa-eye"></i>
                                                    Detail</a>
                                            @else
                                                <a href="{{url('coc/check-in/'.$coc->id)}}"
                                                    class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i>
                                                    Check-In</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
                            @endif
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


    <script type="text/javascript">
        $(document).ready(function () {
            $("#business_area").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable();
        });
        jQuery('#coc_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });

    </script>

@stop