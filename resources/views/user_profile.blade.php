@extends('layout')

@section('css')
        <!-- Treeview css -->
<link href="{{asset('assets/plugins/jstree/style.css')}}" rel="stylesheet" type="text/css"/>

@stop

@section('title')
    <h4 class="page-title">User Profile</h4>
    @stop

    @section('content')
            <!-- start: USER PROFILE -->
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div>
                            <div align="center">
                                <h4>{{Auth::user()->name}}</h4>

                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="user-image">
                                        <div class="fileinput-new thumbnail">
                                            <a href="javacript:" data-toggle="modal"
                                               data-target="#myModal">
                                                @if(Auth::user()->foto!='')
                                                    <img src="{{url('user/foto')}}" alt="user" width="200">
                                                @else
                                                    <img src="{{asset('assets/images/user.jpg')}}" alt="user" width="200">
                                                @endif
                                            </a>
                                        </div>

                                        <div>
                                            <a href="javacript:" data-toggle="modal"
                                               data-target="#myModal"><i class="fa fa-pencil"></i>
                                                Change Photo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="header-title m-t-30 m-b-30">General Information</h4>
                            <table class="table" style="text-align: left;">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Unit</td>
                                    <td>{{Auth::user()->ad_company}}</td>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <td>{{Auth::user()->username}}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>{{Auth::user()->nip}}</td>
                                </tr>
                                <tr>
                                    <td>Personel Number</td>
                                    <td>{{@Auth::user()->strukturJabatan->pernr}}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>{{(Auth::user()->hasRole('komisaris'))?'Dewan Komisaris':@Auth::user()->jabatan}}</td>
                                </tr>
                                <tr>
                                    <td>Bidang</td>
                                    <td>{{@Auth::user()->bidang}}</td>
                                </tr>
                                <tr>
                                    <td>Business Area</td>
                                    <td>{{Auth::user()->business_area.' - '.@Auth::user()->businessArea->description}}</td>
                                </tr>
                                <tr>
                                    <td>Company Code</td>
                                    <td>{{Auth::user()->company_code.' - '.@Auth::user()->companyCode->description}}</td>
                                </tr>
                                <tr>
                                    <td>Role</td>
                                    <td>
                                        @if(Auth::user()->roles->count()>0)
                                            @foreach(Auth::user()->roles as $role)
                                                <span class="label label-sm label-info">{{$role->display_name}}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Signature</td>
                                    <td>
                                        <a  href="javacript:" data-toggle="modal"
                                            data-target="#ttdModal">
                                            @if(Auth::user()->ttd!='')
                                                <img src="{{url('user/ttd')}}" width="250">
                                            @endif
                                            <div>
                                                <i class="fa fa-pencil"></i> Update tandatangan
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <h4 class="header-title m-t-30 m-b-30">Contact Information</h4>
                            <table class="table m-t-10" style="text-align: left;">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>email:</td>
                                    <td>
                                        <a href="mailto:{{Auth::user()->email}}">
                                            {{Auth::user()->email}}
                                        </a></td>
                                </tr>
                                <tr>
                                    <td>phone:</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-7 col-md-8">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">Activity Log</h4>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Aktivitas</th>
                                <th width="200">Date</th>
                                <th>IP Address</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach(Auth::user()->activityLogs()->orderBy('id','desc')->take(10)->get() as $log)
                                <tr>
                                    <td>{{$log->text}}</td>
                                    <td>
                                        {{$log->created_at->format('d-m-Y H:i')}}<br>
                                        <small>{{$log->created_at->diffForHumans()}}</small>
                                    </td>
                                    <td>{{$log->ip_address}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-6 col-xs-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">Posisi Dalam Organisasi</h4>

                        <div id="basicTree">
                            <ul>
                                <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>PT PLN (PERSERO)
                                @if(Auth::user()->orgeh!=null)
                                    <?php $x=0;
                                        $arr_pohon = Auth::user()->getPohonOrganisasi();
                                    ?>
                                    {{-- @if(!Auth::user()->isGM()) --}}
                                        @if(count($arr_pohon)>1)
                                        @foreach($arr_pohon as $org)
                                        <ul>
                                            <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{@$org->stext}}
                                        <?php $x++?>
                                        @endforeach

                                            {{-- @if(Auth::user()->strukturPosisi!=null) --}}

                                                @if(Auth::user()->orgeh != $org->objid)
                                                <ul>
                                                    <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{Auth::user()->bidang}}
                                                @endif
                                                        <ul>
                                                            <li data-jstree='{"opened":true, "icon":"zmdi zmdi-account"}'>{{Auth::user()->jabatan}}
                                                            </li>
                                                        </ul>
                                                @if(Auth::user()->orgeh != $org->objid)
                                                    </li>
                                                </ul>
                                                @endif
                                            {{-- @endif     --}}

                                        @for($y=0;$y<$x;$y++)
                                            </li>
                                        </ul>
                                        @endfor
                                        @else
                                            <ul>
                                                <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{Auth::user()->strukturPosisi->stxt2}}
                                                    <ul>
                                                        <li data-jstree='{"opened":true, "icon":"zmdi zmdi-account"}'>{{Auth::user()->strukturPosisi->stext}}
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        @endif
                                    {{-- @else
                                        <ul>
                                            <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{Auth::user()->strukturPosisi->stxt2}}
                                                <ul>
                                                    <li data-jstree='{"opened":true, "icon":"zmdi zmdi-account"}'>{{Auth::user()->strukturPosisi->stext}}
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    @endif --}}
                                @else
                                    @if(Auth::user()->hasRole('komisaris'))
                                    <ul>
                                        <li data-jstree='{"opened":true, "icon":"zmdi zmdi-account"}'>
                                            Dewan Komisaris
                                        </li>
                                    </ul>
                                    @else
                                    <ul>
                                        <li data-jstree='{"opened":true, "icon":"zmdi zmdi-alert-triangle"}'>
                                            Error! User belum ada struktur organisasi.
                                        </li>
                                    </ul>
                                    @endif
                                @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">Statistik CoC</h4>

                        <div>
                            <small class="text-muted"></small>
                        </div>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th align="center">Tahun</th>
                                <th align="center">CoC yang diikuti</th>
                                <th align="center">Materi CoC yang dibaca</th>
                                <th align="center">Menjadi leader CoC</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x=1;?>
                            @for($tahun=date('Y');$tahun>=2017;$tahun--)
                            <tr>
                                <th scope="row">{{$x++}}</th>
                                <td align="center">{{$tahun}}</td>
                                <td align="center">{{Auth::user()->getJumlahCoCTahun($tahun)}}</td>
                                <td align="center">{{Auth::user()->getJumlahMateriDibacaTahun($tahun)}}</td>
                                <td align="center">{{Auth::user()->getJumlahLeaderCoCTahun($tahun)}}</td>
                            </tr>
                            @endfor
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">Komitmen</h4>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tahun</th>
                                <th>Organisasi</th>
                                <th>Posisi</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x=1;?>
                            @foreach(Auth::user()->komitmenPegawai()->orderBy('tahun','desc')->get() as $komitmen)
                                <tr>
                                    <th scope="row">{{$x++}}</th>
                                    <td>{{@$komitmen->tahun}}</td>
                                    <td>{{@$komitmen->organisasi->stext}}</td>
                                    <td>{{@$komitmen->posisi->stext}}</td>
                                    <td><a href="#"><i class="fa fa-download"></i> Download</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">Temperature Log</h4>

                        <button type="button" class="btn btn-primary waves-effect waves-light" onclick="$('#modalCorona').modal('show');">
                           <span class="btn-label"><i class="fa fa-male"></i>
                           </span>Input Suhu Badan</button>

                        <div style="overflow: auto;height: 500px;" class="m-t-10">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="200">Date</th>
                                    <th>Suhu Badan</th>
                                    <th>Keterangan</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach(Auth::user()->suhuBadan()->where('status','ACTV')->orderBy('id','desc')->take(31)->get() as $log)
                                    <tr>
                                        <td>
                                            {{$log->tanggal->format('d-m-Y H:i')}}<br>
                                            <small>{{$log->tanggal->diffForHumans()}}</small>
                                        </td>
                                        <td>
                                            @if($log->suhu > 37.5)
                                                <span class="text-danger">{{$log->suhu}}</span>
                                            @else
                                                <span>{{$log->suhu}}</span>
                                            @endif

                                        </td>
                                        <td>{{$log->keterangan}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- sample modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        {!! Form::open(['url'=>'user/'.Auth::user()->id.'/update-foto', 'files'=>true]) !!}
        {!! Form::hidden('redirect', 'profile') !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Profile Picture</h4>
                </div>
                <div class="modal-body">

                    <div class="m-l-20">
                        <h6 class="m-b-20 text-muted">File Foto (*.jpg, *.jpeg, *.png)</h6>
                        {!! Form::file('foto', ['class'=>'form-control', 'id'=>'foto']) !!}
                        <small class="text-muted">Ukuran file maksimal 1MB. Resolusi 500x500 px.</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="fa fa-check"></i>
                        Apply
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        {!! Form::close() !!}
    </div><!-- /.modal -->

    <!-- sample modal content -->
    <div id="ttdModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ttdModalLabel" aria-hidden="true">
        {!! Form::open(['url'=>'user/'.Auth::user()->id.'/update-ttd', 'files'=>true]) !!}
        {!! Form::hidden('redirect', 'profile') !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Tandatangan</h4>
                </div>
                <div class="modal-body">

                    <div class="m-l-20">
                        <h6 class="m-b-20 text-muted">File Foto (*.jpg, *.jpeg, *.png)</h6>
                        {!! Form::file('foto', ['class'=>'form-control', 'id'=>'foto']) !!}
                        <small class="text-muted">Ukuran file maksimal 1MB. Resolusi 500x500 px.</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="fa fa-check"></i>
                        Apply
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        {!! Form::close() !!}
    </div><!-- /.modal -->

            <!-- sample modal content -->
            <div id="modalCorona" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCorona" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                            <h4 class="modal-title" id="myModalLabel">Bersama Perangi Corona</h4>
                        </div>
                        <div class="modal-body" align="left">
                            <img src="{{asset('assets/images/corona.jpg')}}" class="img-fluid center" height="70">
                            <div class="m-t-20 p-20">
                                <h4>Semangat Pagi Kawan-Kawan,</h4>
                                <p class="m-t-20">Menghadapi pandemi virus Corona. Silakan catat suhu badan Anda pada form di bawah ini setiap hari.</p>
                                <div align="center">
                                    {!! Form::open(['url'=>'corona/store','class'=>'form-inline']) !!}
                                    {{--                                <form class="form-inline" method="post">--}}
                                    <div class="form-group">
                                        {{--                                        <label for="suhu_badan">Suhu</label>--}}
                                        <input type="number" step="0.1" class="form-control" id="suhu_badan" name="suhu"
                                               placeholder="Suhu Badan (ex: 36.5)" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                                               placeholder="Keterangan (optional)">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    {{--                                </form>--}}
                                    {!! Form::close() !!}

                                </div>
                                <br>
                                <p>Lindungi Diri, Lindungi Sesama. Terimakasih.</p>
                                <p>Salam,<br>
                                    ADMIN KOMANDO
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

    @stop

    @section('javascript')
            <!-- Tree view js -->
    <script src="{{asset('assets/plugins/jstree/jstree.min.js')}}"></script>
    <script src="{{asset('assets/pages/jquery.tree.js')}}"></script>

@stop
