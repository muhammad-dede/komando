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
    {!! Form::open(['url'=>'user-management/user/'.$user->id.'/edit', 'files'=>true]) !!}
    {!! Form::hidden('user_id',$user->id) !!}
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="card-box">

                <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile"
                           role="tab" aria-controls="profile" aria-expanded="true"><h5>Profile</h5></a>
                    </li>
                    {{--@if(Auth::user()->hasRole('root'))--}}
                    <li class="nav-item">
                        <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles"
                           role="tab" aria-controls="roles"><h5>Roles</h5></a>
                    </li>
                    {{--@endif()--}}
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
                                                                {{--<a href="javacript:" data-toggle="modal"--}}
                                                                   {{--data-target="#myModal">--}}
                                                                    <img
                                                                            {{--                                                                        src="{{asset('assets/images/user.jpg')}}"--}}
{{--                                                                            src="{{($user->foto!='') ? url('user/foto-thumb/'.$user->id) : url('user/foto-pegawai-thumb/'.$user->nip)}}"--}}
                                                                            src="{{($user->foto!='') ? url('user/foto-thumb/'.$user->id) : asset('assets/images/user.jpg')}}"
                                                                            width="200"
                                                                            alt="">
                                                                {{--</a>--}}
                                                            </div>

                                                            {{--<div>--}}
                                                                {{--<a href="javacript:" data-toggle="modal"--}}
                                                                   {{--data-target="#myModal"><i class="fa fa-pencil"></i>--}}
                                                                    {{--Change Photo</a>--}}
                                                            {{--</div>--}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <h4 class="header-title m-t-30 m-b-30">General Information</h4>
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <table class="table" style="text-align: left;">
                                                    <thead>
                                                    {{--<tr>--}}
                                                    {{--<th colspan="3">General information</th>--}}
                                                    {{--</tr>--}}
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>Unit *</td>
                                                        <td>
                                                            {!! Form::text('company', $user->ad_company, ['class'=>'form-control']) !!}
                                                            {{--<small class="alert-danger">{{($errors->has('domain'))?$errors->domain->first('required'):''}}</small>--}}
                                                            {{--{{$user->domain}}--}}
                                                        </td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Username *</td>
                                                        <td>
                                                            {!! Form::text('username', $user->username, ['class'=>'form-control']) !!}
                                                            <small class="text-muted">Username active directory sesuai ESS / Email</small>
                                                            {{--{{$user->username}}--}}
                                                        </td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Email *</td>
                                                        <td>
                                                            {!! Form::text('email', $user->email, ['class'=>'form-control']) !!}
                                                            <small class="text-muted">Wajib email korporat (@pln.co.id)</small>
                                                            {{--{{$user->username}}--}}
                                                        </td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>NIP *</td>
                                                        <td>
                                                            {!! Form::text('nip', $user->nip, ['class'=>'form-control']) !!}
                                                            <small class="text-muted">Tanpa tanda spasi atau minus</small>
                                                            {{--{{$user->nip}}--}}
                                                        </td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Jabatan *</td>
{{--                                                        <td>{{$user->ad_title}}</td>--}}
                                                        <td>
                                                            {!! Form::text('posisi', @$user->jabatan, ['class'=>'form-control']) !!}
                                                            <small class="text-muted">Sebutan jabatan pegawai</small>
                                                            {{--{{@$user->strukturPosisi()->stext}}--}}
                                                        </td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Bidang *</td>
{{--                                                        <td>{{$user->ad_title}}</td>--}}
                                                        <td>
                                                            {!! Form::text('bidang', @$user->bidang, ['class'=>'form-control']) !!}
                                                            <small class="text-muted">Sebutan bidang pegawai</small>
                                                            {{--{{@$user->strukturPosisi()->stext}}--}}
                                                        </td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td>Organisasi *</td>
                                                        {{--<td>{{$user->ad_department}}</td>--}}
                                                        <td>
                                                            {!! Form::text('organisasi', @$user->organisasi->stext, ['class'=>'form-control', 'id'=>'organisasi', 'readonly']) !!}
                                                            {!! Form::hidden('orgeh', @$user->orgeh, ['id'=>'orgeh']) !!}
                                                            <small class="text-muted">Pilih posisi dalam organisasi di sebelah kanan untuk mengubah</small>
{{--                                                            {{@$user->strukturPosisi()->stxt2}}--}}
                                                        </td>
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    </tr>
                                                    {{--<tr>--}}
                                                        {{--<td>Company</td>--}}
                                                        {{--<td>{{$user->ad_company}}</td>--}}
                                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Business Area *</td>
                                                        <td>
                                                            {!!
                                                                Form::select(
                                                                    'business_area[]',
                                                                    $businessAreaOpts,
                                                                    $businessAreaSelected,
                                                                    [
                                                                        'class'=>'form-control select2 multiple',
                                                                        'id'=>'business_area',
                                                                        'multiple' => true,
                                                                    ]
                                                                )
                                                            !!}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="col-sm-7 col-md-8">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-box">

                                            <h4 class="header-title m-t-0">Posisi Dalam Organisasi</h4>
                                            {{-- banner warning --}}
                                            <div class="alert alert-warning">
                                                <strong><i class="fa fa-warning"></i></strong> Hanya untuk mengubah data organisasi pegawai Sub Holding dan Anak Perusahaan
                                            </div>
                                            <div style="overflow: auto;height: 700px;">
                                                <div id="tree-container"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="roles" role="tabpanel"
                         aria-labelledby="roles-tab">
                        <div class="col-md-12 col-xs-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <select name="roles[]" class="multi-select" multiple="" id="my_multi_select3">
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}" {{($user->hasRole($role->name))? 'selected' : ''}}>{{$role->display_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <a href="{{url('user-management/user')}}" type="button" class="btn btn-lg btn-primary"><i
                                    class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-lg btn-success pull-right"><i
                                    class="fa fa-save"></i> Save</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {!! Form::close() !!}

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

    <script type="text/javascript">
        $(document).ready(function(){
            //fill data to tree  with AJAX call
            $('#tree-container').jstree({
                //'plugins': ["wholerow", "checkbox"],
                "types" : {
                    "default" : {
                        "icon" : "zmdi zmdi-city-alt"
                    },
                    "demo" : {
                        "icon" : "zmdi zmdi-city-alt"
                    }
                },
                "plugins" : ["types"],
                'core' : {
                    'data' : {
                        "url" : "{{url('ajax/tree/strukorg-lazy?lazy')}}",
                        "data" : function (node) {
                            return { "id" : node.id };
                        },
                        "dataType" : "json" // needed only if you do not supply JSON headers
                    }
                }
            });

            $('#tree-container').on("changed.jstree", function (e, data) {
                $('#organisasi').val(data.instance.get_selected(true)[0].text);
                $('#orgeh').val(data.selected);
            });

            
        });
    </script>

@stop
