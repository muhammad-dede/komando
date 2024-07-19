@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Komitmen {{ (Auth::user()->hasRole(['direksi','komisaris']))?'Direksi dan Dewan Komisaris':'Pegawai' }}</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {{--{!! Form::open(['url'=>'report/commitment']) !!}--}}
                {{--<div class="form-group row">--}}
                    {{--<label for="business_area" class="col-md-1 form-control-label">Unit</label>--}}
                    {{--<div class="col-md-4">--}}
                        {{--{!! Form::select('business_area', $bsAreaList, $ba_selected,--}}
                            {{--['class'=>'form-control select2',--}}
                            {{--'id'=>'business_area']) !!}--}}

                    {{--</div>--}}
                    {{--<div class="col-md-8 button-list">--}}
                        {{--<button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>--}}
                        {{--<a href="{{url('report/commitment/export/'.$ba_selected)}}" id="post" type="submit"--}}
                           {{--class="btn btn-success waves-effect waves-light">--}}
                            {{--<i class="fa fa-file-excel-o"></i> &nbsp;Export</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--{!! Form::close() !!}--}}

                <div class="row">
                    <div class="col-md-12">
                        {{-- <a href="{{url('commitment/buku')}}" id="buku" class="btn btn-success w-lg waves-effect waves-light">
                            <i class="fa fa-book"></i> &nbsp;Buku Pedoman Perilaku</a> --}}

                        <a href="{{asset('assets/doc/pedoman_perilaku_2022.pdf')}}" id="pdf" target="_blank" class="btn btn-danger w-lg waves-effect waves-light">
                            <i class="fa fa-file-pdf-o"></i> &nbsp;PDF Pedoman Perilaku</a>

                    </div>
                </div>
                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="50" style="text-align: center">No.</th>
                                <th style="text-align: center">Tahun</th>
                                <th style="text-align: center">Nama</th>
                                <th style="text-align: center">NIP</th>
                                <th style="text-align: center">Jabatan</th>
                                <th style="text-align: center">Bidang</th>
                                <th style="text-align: center">Progress</th>
                                <th style="text-align: center">Commitment</th>
                            </tr>
                            </thead>


                            <tbody>
                            {{--@if(isset($user_list))--}}
                                <?php
                                    $x=1;
//                                    $jml_perilaku = App\PedomanPerilaku::all()->count();
                                ?>
                            @for($y=2017;$y<=date('Y');$y++)
                                <?php

//                                if($y<2019){
//                                    $jml_perilaku = 18;
//                                }
//                                else{
                                    $jml_perilaku = env('JML_PEDOMAN', 14);
//                                }

                                $komitmen_pegawai = Auth::user()->komitmenPegawai()->where('tahun',$y)->get();
                                ?>
                                @if($komitmen_pegawai->count()==0)
                                    <?php
                                    $jml_jawaban = Auth::user()->perilakuPegawai()->where('tahun',$y)->get()->count();
                                    $persen = ($jml_jawaban/$jml_perilaku)*100;
                                    if($persen>100) $persen = 100;
                                    ?>
                                    <tr>
                                        <td>{{$x++}}</td>
                                        <td>{{$y}}</td>
                                        <td>{{Auth::user()->name}}</td>
                                        <td>{{Auth::user()->nip}}</td>
                                        <td>
                                            @if(Auth::user()->hasRole('komisaris'))
                                                Dewan Komisaris
                                            @else
                                            {{@Auth::user()->strukturPosisi()->objid}} - {{@Auth::user()->strukturPosisi()->stext}}
                                            @endif
                                        </td>
                                        <td>{{@Auth::user()->strukturOrganisasi()->objid}} - {{@Auth::user()->strukturOrganisasi()->stext}}</td>
                                        <td align="center">{{number_format($persen,2)}}%</td>
                                        <?php
                                        //$commit = $user->getKomitmenTahunini();
                                        ?>
                                        <td>
                                            <a href="{{ url('commitment/pedoman-perilaku/tahun/'.$y) }}" class="btn btn-primary">Tanda Tangan</a>
                                        </td>
                                    </tr>
                                @endif
                                @foreach($komitmen_pegawai as $komit)
                                    <?php
                                    $jml_jawaban = $komit->user->perilakuPegawai()->where('tahun',$komit->tahun)->get()->count();
                                    $persen = ($jml_jawaban/$jml_perilaku)*100;
                                    if($persen>100) $persen = 100;
                                    ?>
                                    <tr>
                                        <td>{{$x++}}</td>
                                        <td>{{$komit->tahun}}</td>
                                        <td>{{$komit->user->name}}</td>
                                        <td>{{$komit->user->nip}}</td>
                                        <td>
                                            @if(Auth::user()->hasRole('komisaris'))
                                                Dewan Komisaris
                                            @else
                                            {{@$komit->user->strukturPosisi()->objid}} - {{@$komit->user->strukturPosisi()->stext}}
                                            @endif
                                        </td>
                                        <td>{{@$komit->user->strukturOrganisasi()->objid}} - {{@$komit->user->strukturOrganisasi()->stext}}</td>
                                        {{--<td align="center">{{number_format($komit->jml_perilaku,2)}}%</td>--}}
                                        <td align="center">{{number_format($persen,2)}}%</td>
                                        <?php
                                        //$commit = $user->getKomitmenTahunini();
                                        ?>
                                        <td>{!! ($komit!=null)? $komit->created_at->format('Y-m-d H:i').'<br><small class=\'text-muted\'>'.$komit->created_at->diffForHumans().'</small>' : '' !!}</td>
                                    </tr>
                                @endforeach
                            @endfor
                            {{--@endif--}}

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
//        $(document).ready(function () {
//            $("#business_area").select2();
//        });
        $(document).ready(function () {
            $('#datatable').DataTable();
        });

    </script>

@stop