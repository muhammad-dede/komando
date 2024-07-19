@extends('layout')

@section('css')
        <!-- Treeview css -->
<link href="{{asset('assets/plugins/jstree/style.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>


@stop

@section('title')
    <h4 class="page-title">User</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="card-box">

                <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile"
                           role="tab" aria-controls="profile" aria-expanded="true"><h5>Profile</h5></a>
                    </li>
                    @if(Auth::user()->hasRole('root'))
                    <li class="nav-item">
                        <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles"
                           role="tab" aria-controls="roles"><h5>Roles</h5></a>
                    </li>
                    @endif()
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link" id="organisasi-tab" data-toggle="tab" href="#organisasi"--}}
                    {{--role="tab" aria-controls="organisasi">Struktur</a>--}}
                    {{--</li>--}}
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="profile"
                         aria-labelledby="profile-tab">
                        <!-- start: USER PROFILE -->
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="card-box">

                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div>
                                                <div align="center">
                                                    <h4>{{$user->name}}</h4>

                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="user-image">
                                                            <div class="fileinput-new thumbnail">
                                                                <a href="javacript:" data-toggle="modal"
                                                                   data-target="#myModal">
                                                                    @if($user->foto!='')
                                                                        <img src="{{url('user/foto/'.$user->id)}}" alt="user" width="200">
                                                                    @else
                                                                        <img src="{{asset('assets/images/user.jpg')}}" alt="user" width="200">
                                                                    @endif
                                                                    {{--<img--}}
                                                                            {{--                                                                        src="{{asset('assets/images/user.jpg')}}"--}}
{{--                                                                            src="{{($user->foto!='') ? url('user/foto-thumb/'.$user->id) : url('user/foto-pegawai-thumb/'.$user->nip)}}"--}}
                                                                            {{--src="{{($user->foto!='') ? url('user/foto-thumb/'.$user->id) : asset('assets/images/user.jpg')}}"--}}
                                                                            {{--src="{{($user->foto!='') ? url('user/foto/'.$user->id) : url('user/foto-pegawai/'.$user->nip)}}"--}}
                                                                            {{--width="200"--}}
                                                                            {{--alt="">--}}
                                                                </a>
                                                            </div>

                                                            <div>
                                                                <a href="javacript:" data-toggle="modal"
                                                                   data-target="#myModal"><i class="fa fa-pencil"></i>
                                                                    Change Photo</a>
                                                            </div>
                                                            <div>
                                                                @if(Auth::user()->hasRole('root'))
                                                                    <button class="btn btn-primary m-t-10" onclick="window.location.href='{{url('impersonation/'.$user->id)}}'"><i class="fa fa-external-link"></i> Impersonate</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <h4 class="header-title m-t-30 m-b-30">General Information</h4>
                                                <table class="table" style="text-align: left;">
                                                    <thead>
                                                    {{--<tr>--}}
                                                    {{--<th colspan="3">General information</th>--}}
                                                    {{--</tr>--}}
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>Unit</td>
                                                        <td>{{$user->ad_company}}</td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Username</td>
                                                        <td>{{$user->username}}</td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>NIP</td>
                                                        <td>{{$user->nip}}</td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Personel Number</td>
                                                        <td>{{@$user->strukturJabatan->pernr}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jabatan</td>
{{--                                                        <td>{{$user->ad_title}}</td>--}}
                                                        <td>{{@$user->jabatan}}</td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Bidang</td>
                                                        {{--<td>{{$user->ad_department}}</td>--}}
                                                        <td>{{@$user->bidang}}</td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    {{--<tr>--}}
                                                        {{--<td>Company</td>--}}
                                                        {{--<td>{{$user->ad_company}}</td>--}}
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Business Area</td>
                                                        <td>{{$user->business_area.' - '.@$user->businessArea->description}}</td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Company Code</td>
                                                        <td>{{$user->company_code.' - '.@$user->companyCode->description}}</td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Role</td>
                                                        <td>
                                                            @if($user->roles->count()>0)
                                                                @foreach($user->roles as $role)
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
                                                                @if($user->ttd!='')
                                                                    <img src="{{url('user/ttd/'.$user->id)}}" width="250">
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
                                                            <a href="mailto:{{$user->email}}">
                                                                {{$user->email}}
                                                            </a></td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>phone:</td>
                                                        <td></td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
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
                                                @foreach($user->activityLogs()->orderBy('id','desc')->take(10)->get() as $log)
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
                                                    <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>PT
                                                        PLN (PERSERO)
                                                        @if($user->orgeh!=null)
                                                            <?php $x=0;
                                                            $arr_pohon = $user->getPohonOrganisasi();
                                                            //dd(count($arr_pohon));
                                                            ?>
                                                            {{-- @if(!$user->isGM()) --}}
                                                                @if(count($arr_pohon)>1)
                                                                    @foreach($user->getPohonOrganisasi() as $org)
                                                                    <ul>
                                                                        <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{@$org->stext}}
                                                                            <?php $x++?>
                                                                            @endforeach
                                                                            @if(@$user->orgeh != @$org->objid)
                                                                            <ul>
                                                                                <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{@$user->bidang}}
                                                                            @endif
                                                                                    <ul>
                                                                                        <li data-jstree='{"opened":true, "icon":"zmdi zmdi-account"}'>{{@$user->jabatan}}
                                                                                        </li>
                                                                                    </ul>
                                                                            @if(@$user->orgeh != @$org->objid)
                                                                                </li>
                                                                            </ul>
                                                                            @endif
                                                                            @for($y=0;$y<$x;$y++)
                                                                        </li>
                                                                    </ul>
                                                                    @endfor
                                                                @else
                                                                    <ul>
                                                                        <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{@$user->strukturPosisi()->stxt2}}
                                                                            <ul>
                                                                                <li data-jstree='{"opened":true, "icon":"zmdi zmdi-account"}'>{{@$user->strukturPosisi()->stext}}
                                                                                </li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                @endif
                                                            {{-- @else
                                                                <ul>
                                                                    <li data-jstree='{"opened":true, "icon":"zmdi zmdi-city-alt"}'>{{@$user->strukturPosisi()->stxt2}}
                                                                        <ul>
                                                                            <li data-jstree='{"opened":true, "icon":"zmdi zmdi-account"}'>{{@$user->strukturPosisi()->stext}}
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            @endif --}}

                                                        @else
                                                            <ul>
                                                                <li data-jstree='{"opened":true, "icon":"zmdi zmdi-alert-triangle"}'>
                                                                    Error! User belum ada struktur organisasi.
                                                                </li>
                                                            </ul>
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
                                                        <td align="center">{{$user->getJumlahCoCTahun($tahun)}}</td>
                                                        <td align="center">{{$user->getJumlahMateriDibacaTahun($tahun)}}</td>
                                                        <td align="center">{{$user->getJumlahLeaderCoCTahun($tahun)}}</td>
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
                                                <?php $x = 1;?>
                                                @foreach($user->komitmenPegawai()->orderBy('tahun','desc')->get() as $komitmen)
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

                                            <div style="overflow: auto;height: 500px;">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th width="200">Date</th>
                                                        <th>Suhu Badan</th>
                                                        <th>Keterangan</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($user->suhuBadan()->where('status','ACTV')->orderBy('id','desc')->take(31)->get() as $log)
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
                    </div>
                    @if(Auth::user()->hasRole('root'))
                    <div class="tab-pane fade" id="roles" role="tabpanel"
                         aria-labelledby="roles-tab">
                        <div class="col-md-12 col-xs-12">
                            <div class="card-box">
                                {!! Form::open(['url'=>'user-management/user/'.$user->id.'/update-role']) !!}
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        {{--<h6>Searchable</h6>--}}
                                        {{--<p class="text-muted m-b-20 font-13">--}}
                                        {{--Use a <code>--}}
                                        {{--&lt;select multiple /&gt;</code>--}}
                                        {{--as your input element for a tags input, to gain true multivalue support.--}}
                                        {{--</p>--}}

                                        <select name="roles[]" class="multi-select" multiple="" id="my_multi_select3">
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}" {{($user->hasRole($role->name))? 'selected' : ''}}>{{$role->display_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Apply
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    {{--<div class="tab-pane fade" id="organisasi" role="tabpanel"--}}
                    {{--aria-labelledby="organisasi-tab">--}}
                    {{--Struktur--}}
                    {{--</div>--}}

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{url('user-management/user')}}" type="button" class="btn btn-lg btn-primary"><i
                                    class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- sample modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        {!! Form::open(['url'=>'user/'.$user->id.'/update-foto', 'files'=>true]) !!}
        {!! Form::hidden('redirect', 'user-management/user/'.$user->id) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Profile Picture</h4>
                </div>
                <div class="modal-body">
                    {{--<h4>Text in a modal</h4>--}}
                    {{--<p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>--}}

                    <div class="m-l-20">
                        <h6 class="m-b-20 text-muted">File Foto (*.jpg, *.jpeg, *.png)</h6>
                        {{--<label class="file">--}}
                        {{--<input type="file" id="file">--}}
                        {!! Form::file('foto', ['class'=>'form-control', 'id'=>'foto']) !!}
                        {{--<span class="file-custom"></span>--}}
                        {{--</label>--}}
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
        {!! Form::open(['url'=>'user/'.$user->id.'/update-ttd', 'files'=>true]) !!}
        {!! Form::hidden('redirect', 'user-management/user/'.$user->id) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Tandatangan</h4>
                </div>
                <div class="modal-body">
                    {{--<h4>Text in a modal</h4>--}}
                    {{--<p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>--}}

                    <div class="m-l-20">
                        <h6 class="m-b-20 text-muted">File Foto (*.jpg, *.jpeg, *.png)</h6>
                        {{--<label class="file">--}}
                        {{--<input type="file" id="file">--}}
                        {!! Form::file('foto', ['class'=>'form-control', 'id'=>'foto']) !!}
                        {{--<span class="file-custom"></span>--}}
                        {{--</label>--}}
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


    @stop

    @section('javascript')
            <!-- Tree view js -->
    <script src="{{asset('assets/plugins/jstree/jstree.min.js')}}"></script>
    <script src="{{asset('assets/pages/jquery.tree.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/plugins/multiselect/js/jquery.multi-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/plugins/jquery-quicksearch/jquery.quicksearch.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"
            type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('assets/pages/jquery.formadvanced.init.js')}}"></script>



@stop
