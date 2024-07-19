@extends('layout')

@section('css')
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" media="screen">
@stop

@section('title')
    {{--<h1 class="mainTitle">User Profile</h1>--}}
    {{--<span class="mainDescription">display your personal information</span>--}}
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">Add New User</h1>
            <span class="mainDescription">create new external user (non-domain PLN)</span>
        </div>
        <ol class="breadcrumb">
            <li>
                <span>User Management</span>
            </li>
            <li>
                <span><a href="{{url('user-management/user')}}">User</a></span>
            </li>
            <li class="active">
                <span>Create</span>
            </li>
        </ol>
    </div>
    @stop

    @section('content')
    {!! Form::open(['url'=>'user-management/user/create']) !!}
            <!-- start: USER PROFILE -->
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="form-group col-md-12">
                <div class="col-md-2">
                    <label class="control-label pull-right" for="id_user">ID</label>

                </div>
                {{--<label class="col-sm-2 control-label pull-right" for="id_user">--}}
                {{--ID--}}
                {{--</label>--}}
                <div class="col-md-2">
                    {!! Form::text('id_user', '%0000000001', ['class'=>'form-control underline', 'readonly'=>'', 'id'=>'id_user']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::text('system_status', 'CRTD', ['class'=>'form-control underline', 'readonly'=>'']) !!}
                </div>
                <div class="col-sm-4">
                    <p>
                        <button type="submit" class="btn btn-wide btn-primary"><i class="fa fa-floppy-o"></i> Save
                        </button>
                        <a class="btn btn-wide btn-default" href="{{url('user-management/user')}}"><i
                                    class="fa fa-remove"></i> Cancel</a>

                    </p>
                </div>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">
                <div class="tabbable">
                    <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
                        {{--<li class="active">--}}
                            {{--<a data-toggle="tab" href="#panel_overview">--}}
                                {{--Overview--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        <li class="active">
                            <a data-toggle="tab" href="#panel_edit_account">
                                Create Account
                            </a>
                        </li>
                        {{--<li>--}}
                        {{--<a data-toggle="tab" href="#panel_projects">--}}
                        {{--Assign Role--}}
                        {{--</a>--}}
                        {{--</li>--}}
                    </ul>
                    <div class="tab-content">
                        {{--<div id="panel_overview" class="tab-pane fade in active">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-sm-5 col-md-4">--}}
                                    {{--<div class="user-left">--}}
                                        {{--<div class="center">--}}
                                            {{--<h4>{{$user->name}}</h4>--}}

                                            {{--<div class="fileinput fileinput-new" data-provides="fileinput">--}}
                                                {{--<div class="user-image">--}}
                                                    {{--<div class="fileinput-new thumbnail"><img--}}
                                                                {{--src="{{asset('assets/images/user.jpg')}}" alt="">--}}
                                                    {{--</div>--}}
                                                    {{--<div class="fileinput-preview fileinput-exists thumbnail"></div>--}}
                                                    {{--<div class="user-image-buttons">--}}
                                                    {{--<span class="btn btn-azure btn-file btn-sm"><span class="fileinput-new"><i class="fa fa-pencil"></i></span><span class="fileinput-exists"><i class="fa fa-pencil"></i></span>--}}
                                                    {{--<input type="file">--}}
                                                    {{--</span>--}}
                                                    {{--<a href="#" class="btn fileinput-exists btn-red btn-sm" data-dismiss="fileinput">--}}
                                                    {{--<i class="fa fa-times"></i>--}}
                                                    {{--</a>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<hr>--}}
                                            {{--<div class="social-icons block">--}}
                                                {{--<ul>--}}
                                                    {{--<li data-placement="top" data-original-title="Twitter"--}}
                                                        {{--class="social-twitter tooltips">--}}
                                                        {{--<a href="http://www.twitter.com" target="_blank">--}}
                                                            {{--Twitter--}}
                                                        {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li data-placement="top" data-original-title="Facebook"--}}
                                                        {{--class="social-facebook tooltips">--}}
                                                        {{--<a href="http://facebook.com" target="_blank">--}}
                                                            {{--Facebook--}}
                                                        {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li data-placement="top" data-original-title="Google"--}}
                                                        {{--class="social-google tooltips">--}}
                                                        {{--<a href="http://google.com" target="_blank">--}}
                                                            {{--Google+--}}
                                                        {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li data-placement="top" data-original-title="LinkedIn"--}}
                                                        {{--class="social-linkedin tooltips">--}}
                                                        {{--<a href="http://linkedin.com" target="_blank">--}}
                                                            {{--LinkedIn--}}
                                                        {{--</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li data-placement="top" data-original-title="Github" class="social-github tooltips">--}}
                                                    {{--<a href="#" target="_blank">--}}
                                                    {{--Github--}}
                                                    {{--</a>--}}
                                                    {{--</li>--}}
                                                {{--</ul>--}}
                                            {{--</div>--}}
                                            {{--<hr>--}}
                                        {{--</div>--}}
                                        {{--<table class="table table-condensed">--}}
                                            {{--<thead>--}}
                                            {{--<tr>--}}
                                                {{--<th colspan="3">Contact Information</th>--}}
                                            {{--</tr>--}}
                                            {{--</thead>--}}
                                            {{--<tbody>--}}
                                            {{--<tr>--}}
                                            {{--<td>url</td>--}}
                                            {{--<td>--}}
                                            {{--<a href="#">--}}
                                            {{--www.example.com--}}
                                            {{--</a></td>--}}
                                            {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>email:</td>--}}
                                                {{--<td>--}}
                                                    {{--<a href="mailto:{{$user->email}}">--}}
                                                        {{--{{$user->email}}--}}
                                                    {{--</a></td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>phone:</td>--}}
                                                {{--<td></td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                            {{--<td>skye</td>--}}
                                            {{--<td>--}}
                                            {{--<a href="">--}}
                                            {{--peterclark82--}}
                                            {{--</a></td>--}}
                                            {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--</tbody>--}}
                                        {{--</table>--}}
                                        {{--<table class="table">--}}
                                            {{--<thead>--}}
                                            {{--<tr>--}}
                                                {{--<th colspan="3">General information</th>--}}
                                            {{--</tr>--}}
                                            {{--</thead>--}}
                                            {{--<tbody>--}}
                                            {{--<tr>--}}
                                                {{--<td>NIP</td>--}}
                                                {{--<td>{{$user->ad_employee_number}}</td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>Position</td>--}}
                                                {{--<td>{{$user->ad_title}}</td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>Department</td>--}}
                                                {{--<td>{{$user->ad_department}}</td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>Company</td>--}}
                                                {{--<td>{{$user->ad_company}}</td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>Busniness Area</td>--}}
                                                {{--<td>{{$user->bussiness_area}}</td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>Company Code</td>--}}
                                                {{--<td>{{$user->company_code}}</td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                            {{--<td>Last Logged In</td>--}}
                                            {{--<td>56 min</td>--}}
                                            {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                            {{--<td>Position</td>--}}
                                            {{--<td>Senior Marketing Manager</td>--}}
                                            {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                            {{--<td>Supervisor</td>--}}
                                            {{--<td>--}}
                                            {{--<a href="#">--}}
                                            {{--Kenneth Ross--}}
                                            {{--</a></td>--}}
                                            {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td>Role</td>--}}
                                                {{--<td>--}}
                                                    {{--@if($user->roles->count()>0)--}}
                                                        {{--@foreach($user->roles as $role)--}}
                                                            {{--<span class="label label-sm label-info">{{$role->display_name}}</span>--}}
                                                        {{--@endforeach--}}
                                                    {{--@endif--}}
                                                {{--</td>--}}
                                                {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                            {{--</tr>--}}
                                            {{--</tbody>--}}
                                        {{--</table>--}}
                                        {{--<table class="table">--}}
                                        {{--<thead>--}}
                                        {{--<tr>--}}
                                        {{--<th colspan="3">Additional information</th>--}}
                                        {{--</tr>--}}
                                        {{--</thead>--}}
                                        {{--<tbody>--}}
                                        {{--<tr>--}}
                                        {{--<td>Birth</td>--}}
                                        {{--<td></td>--}}
                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                        {{--<td>Groups</td>--}}
                                        {{--<td></td>--}}
                                        {{--<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>--}}
                                        {{--</tr>--}}
                                        {{--</tbody>--}}
                                        {{--</table>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-7 col-md-8">--}}
                                    {{--<div class="row space20">--}}
                                    {{--<div class="col-sm-3">--}}
                                    {{--<button class="btn btn-icon margin-bottom-5 margin-bottom-5 btn-block">--}}
                                    {{--<i class="ti-layers-alt block text-primary text-extra-large margin-bottom-10"></i>--}}
                                    {{--Projects--}}
                                    {{--</button>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-3">--}}
                                    {{--<button class="btn btn-icon margin-bottom-5 btn-block">--}}
                                    {{--<i class="ti-comments block text-primary text-extra-large margin-bottom-10"></i>--}}
                                    {{--Messages <span class="badge badge-danger"> 23 </span>--}}
                                    {{--</button>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-3">--}}
                                    {{--<button class="btn btn-icon margin-bottom-5 btn-block">--}}
                                    {{--<i class="ti-calendar block text-primary text-extra-large margin-bottom-10"></i>--}}
                                    {{--Calendar--}}
                                    {{--</button>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-3">--}}
                                    {{--<button class="btn btn-icon margin-bottom-5 btn-block">--}}
                                    {{--<i class="ti-flag block text-primary text-extra-large margin-bottom-10"></i>--}}
                                    {{--Notifications--}}
                                    {{--</button>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="panel panel-white" id="activities">--}}
                                        {{--<div class="panel-heading border-light">--}}
                                            {{--<h4 class="panel-title text-primary">Recent Activities</h4>--}}
                                            {{--<paneltool class="panel-tools" tool-collapse="tool-collapse"--}}
                                                       {{--tool-refresh="load1" tool-dismiss="tool-dismiss"></paneltool>--}}
                                        {{--</div>--}}
                                        {{--<div collapse="activities" ng-init="activities=false" class="panel-wrapper">--}}
                                            {{--<div class="panel-body" style="height: 800px; overflow: auto;">--}}
                                                {{--<ul class="timeline-xs">--}}
                                                    {{--@if($user->activityLogs->count()>0)--}}
                                                        {{--@foreach($user->activityLogs as $log)--}}
                                                            {{--<li class="timeline-item {{$log->type}}">--}}
                                                                {{--<div class="margin-left-15">--}}
                                                                    {{--<div class="text-muted text-small">--}}
                                                                        {{--{{$log->created_at->diffForHumans()}}--}}
                                                                    {{--</div>--}}
                                                                    {{--<p>--}}
                                                                        {{--{!! $log->text !!}--}}
                                                                    {{--</p>--}}
                                                                {{--</div>--}}
                                                            {{--</li>--}}
                                                        {{--@endforeach--}}
                                                    {{--@else--}}
                                                        {{--No activity--}}
                                                    {{--@endif--}}
                                                    {{--@for($x=0;$x<15;$x++)--}}
                                                    {{--<li class="timeline-item">--}}
                                                    {{--<div class="margin-left-15">--}}
                                                    {{--<div class="text-muted text-small">--}}
                                                    {{--12:00--}}
                                                    {{--</div>--}}
                                                    {{--<p>--}}
                                                    {{--Test--}}
                                                    {{--</p>--}}
                                                    {{--</div>--}}
                                                    {{--</li>--}}
                                                    {{--@endfor--}}

                                                {{--</ul>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="panel panel-white space20">--}}
                                    {{--<div class="panel-heading">--}}
                                    {{--<h4 class="panel-title">Recent Tweets</h4>--}}
                                    {{--</div>--}}
                                    {{--<div class="panel-body">--}}
                                    {{--<ul class="ltwt">--}}
                                    {{--<li class="ltwt_tweet">--}}
                                    {{--<p class="ltwt_tweet_text">--}}
                                    {{--<a href class="text-info">--}}
                                    {{--@Shakespeare--}}
                                    {{--</a>--}}
                                    {{--Some are born great, some achieve greatness, and some have greatness thrust upon them.--}}
                                    {{--</p>--}}
                                    {{--<span class="block text-light"><i class="fa fa-fw fa-clock-o"></i> 2 minuts ago</span>--}}
                                    {{--</li>--}}
                                    {{--</ul>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div id="panel_edit_account" class="tab-pane fade in active">
                            {{--<form action="#" role="form" id="form">--}}
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>
                                            Account Info
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">
                                                        Domain
                                                    </label>
                                                    <input type="text" placeholder="Domain"
                                                           class="form-control underline" id="domain" name="domain"
                                                           value="esdm"
                                                           readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">
                                                        Username
                                                    </label>
                                                    <input type="text" placeholder="Username"
                                                           class="form-control underline" id="username"
                                                           name="username" value="{{ old('username') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">
                                                        Name
                                                    </label>
                                                    <input type="text" placeholder="Name"
                                                           class="form-control underline" id="name" name="name"
                                                           value="{{ old('name') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">
                                                        Email Address
                                                    </label>
                                                    <input type="email" placeholder="Email"
                                                           class="form-control underline" id="email" name="email"
                                                           value="{{ old('email') }}">
                                                </div>
                                                {{--<div class="form-group">--}}
                                                {{--<label class="control-label">--}}
                                                {{--Phone--}}
                                                {{--</label>--}}
                                                {{--<input type="email" placeholder="(641)-734-4763" class="form-control" id="phone" name="email">--}}
                                                {{--</div>--}}
                                                {{--@if($user->domain=='esdm')--}}
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            Password
                                                        </label>
                                                        <input type="password" placeholder="password"
                                                               class="form-control underline" name="password"
                                                               id="password" value="{{ old('password') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            Confirm Password
                                                        </label>
                                                        <input type="password" placeholder="password"
                                                               class="form-control underline" id="password_again"
                                                               name="password_again" value="{{ old('password_again') }}">
                                                    </div>
                                                {{--@endif--}}
                                            </div>
                                            {{--<div class="col-md-6">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label class="control-label">--}}
                                            {{--Gender--}}
                                            {{--</label>--}}
                                            {{--<div class="clip-radio radio-primary">--}}
                                            {{--<input type="radio" value="female" name="gender" id="us-female">--}}
                                            {{--<label for="us-female">--}}
                                            {{--Female--}}
                                            {{--</label>--}}
                                            {{--<input type="radio" value="male" name="gender" id="us-male" checked>--}}
                                            {{--<label for="us-male">--}}
                                            {{--Male--}}
                                            {{--</label>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="row">--}}
                                            {{--<div class="col-md-4">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label class="control-label">--}}
                                            {{--Zip Code--}}
                                            {{--</label>--}}
                                            {{--<input class="form-control" placeholder="12345" type="text" name="zipcode" id="zipcode">--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-8">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label class="control-label">--}}
                                            {{--City--}}
                                            {{--</label>--}}
                                            {{--<input class="form-control tooltips" placeholder="London (UK)" type="text" data-original-title="We'll display it when you write reviews" data-rel="tooltip"  title="" data-placement="top" name="city" id="city">--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label>--}}
                                            {{--Image Upload--}}
                                            {{--</label>--}}
                                            {{--<div class="fileinput fileinput-new" data-provides="fileinput">--}}
                                            {{--<div class="fileinput-new thumbnail"><img src="assets/images/avatar-1-xl.jpg" alt="">--}}
                                            {{--</div>--}}
                                            {{--<div class="fileinput-preview fileinput-exists thumbnail"></div>--}}
                                            {{--<div class="user-edit-image-buttons">--}}
                                            {{--<span class="btn btn-azure btn-file"><span class="fileinput-new"><i class="fa fa-picture"></i> Select image</span><span class="fileinput-exists"><i class="fa fa-picture"></i> Change</span>--}}
                                            {{--<input type="file">--}}
                                            {{--</span>--}}
                                            {{--<a href="#" class="btn fileinput-exists btn-red" data-dismiss="fileinput">--}}
                                            {{--<i class="fa fa-times"></i> Remove--}}
                                            {{--</a>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    {{--<fieldset>--}}
                                        {{--<legend>--}}
                                            {{--User Location--}}
                                        {{--</legend>--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-md-12">--}}

                                                {{--<div class="form-group">--}}
                                                    {{--<label class="control-label">--}}
                                                        {{--Business Area--}}
                                                    {{--</label>--}}
                                                        {{--{!! Form::select('business_area', $bsAreaList, old('business_area'),--}}
                                                        {{--['class'=>'js-example-placeholder-single js-states form-control',--}}
                                                        {{--'id'=>'business_area', 'style'=>'width: 500px;']) !!}--}}
                                                    {{--<select class="js-example-placeholder-single js-states form-control" id="business_area"--}}
                                                    {{--name="business_area" style="width: 500px;">--}}
                                                    {{--<option></option>--}}
                                                    {{--<optgroup label="Alaskan/Hawaiian Time Zone">--}}
                                                    {{--<option value="AK">Alaska</option>--}}
                                                    {{--<option value="HI">Hawaii</option>--}}
                                                    {{--</optgroup>--}}
                                                    {{--<optgroup label="Pacific Time Zone">--}}
                                                    {{--<option value="CA">California</option>--}}
                                                    {{--<option value="NV">Nevada</option>--}}
                                                    {{--<option value="OR">Oregon</option>--}}
                                                    {{--<option value="WA">Washington</option>--}}
                                                    {{--</optgroup>--}}
                                                    {{--<optgroup label="Mountain Time Zone">--}}
                                                    {{--<option value="AZ">Arizona</option>--}}
                                                    {{--<option value="CO">Colorado</option>--}}
                                                    {{--<option value="ID">Idaho</option>--}}
                                                    {{--<option value="MT">Montana</option>--}}
                                                    {{--<option value="NE">Nebraska</option>--}}
                                                    {{--<option value="NM">New Mexico</option>--}}
                                                    {{--<option value="ND">North Dakota</option>--}}
                                                    {{--<option value="UT">Utah</option>--}}
                                                    {{--<option value="WY">Wyoming</option>--}}
                                                    {{--</optgroup>--}}
                                                    {{--<optgroup label="Central Time Zone">--}}
                                                    {{--<option value="AL">Alabama</option>--}}
                                                    {{--<option value="AR">Arkansas</option>--}}
                                                    {{--<option value="IL">Illinois</option>--}}
                                                    {{--<option value="IA">Iowa</option>--}}
                                                    {{--<option value="KS">Kansas</option>--}}
                                                    {{--<option value="KY">Kentucky</option>--}}
                                                    {{--<option value="LA">Louisiana</option>--}}
                                                    {{--<option value="MN">Minnesota</option>--}}
                                                    {{--<option value="MS">Mississippi</option>--}}
                                                    {{--<option value="MO">Missouri</option>--}}
                                                    {{--<option value="OK">Oklahoma</option>--}}
                                                    {{--<option value="SD">South Dakota</option>--}}
                                                    {{--<option value="TX">Texas</option>--}}
                                                    {{--<option value="TN">Tennessee</option>--}}
                                                    {{--<option value="WI">Wisconsin</option>--}}
                                                    {{--</optgroup>--}}
                                                    {{--<optgroup label="Eastern Time Zone">--}}
                                                    {{--<option value="CT">Connecticut</option>--}}
                                                    {{--<option value="DE">Delaware</option>--}}
                                                    {{--<option value="FL">Florida</option>--}}
                                                    {{--<option value="GA">Georgia</option>--}}
                                                    {{--<option value="IN">Indiana</option>--}}
                                                    {{--<option value="ME">Maine</option>--}}
                                                    {{--<option value="MD">Maryland</option>--}}
                                                    {{--<option value="MA">Massachusetts</option>--}}
                                                    {{--<option value="MI">Michigan</option>--}}
                                                    {{--<option value="NH">New Hampshire</option>--}}
                                                    {{--<option value="NJ">New Jersey</option>--}}
                                                    {{--<option value="NY">New York</option>--}}
                                                    {{--<option value="NC">North Carolina</option>--}}
                                                    {{--<option value="OH">Ohio</option>--}}
                                                    {{--<option value="PA">Pennsylvania</option>--}}
                                                    {{--<option value="RI">Rhode Island</option>--}}
                                                    {{--<option value="SC">South Carolina</option>--}}
                                                    {{--<option value="VT">Vermont</option>--}}
                                                    {{--<option value="VA">Virginia</option>--}}
                                                    {{--<option value="WV">West Virginia</option>--}}
                                                    {{--</optgroup>--}}
                                                    {{--</select>--}}
                                                    {{--<input type="text" placeholder="Business Area" class="form-control underline" id="business_area" name="business_area" value="{{$user->business_area}}">--}}
                                                {{--</div>--}}

                                                {{--<div class="form-group">--}}
                                                {{--<label class="control-label">--}}
                                                {{--Twitter--}}
                                                {{--</label>--}}
                                                {{--<span class="input-icon">--}}
                                                {{--<input class="form-control" type="text" placeholder="Text Field">--}}
                                                {{--<i class="fa fa-twitter"></i> </span>--}}
                                                {{--</div>--}}
                                                {{--<div class="form-group">--}}
                                                {{--<label class="control-label">--}}
                                                {{--Facebook--}}
                                                {{--</label>--}}
                                                {{--<span class="input-icon">--}}
                                                {{--<input class="form-control" type="text" placeholder="Text Field">--}}
                                                {{--<i class="fa fa-facebook"></i> </span>--}}
                                                {{--</div>--}}
                                                {{--<div class="form-group">--}}
                                                {{--<label class="control-label">--}}
                                                {{--Google Plus--}}
                                                {{--</label>--}}
                                                {{--<span class="input-icon">--}}
                                                {{--<input class="form-control" type="text" placeholder="Text Field">--}}
                                                {{--<i class="fa fa-google-plus"></i> </span>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-6">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label class="control-label">--}}
                                            {{--Github--}}
                                            {{--</label>--}}
                                            {{--<span class="input-icon">--}}
                                            {{--<input class="form-control" type="text" placeholder="Text Field">--}}
                                            {{--<i class="fa fa-github"></i> </span>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label class="control-label">--}}
                                            {{--Linkedin--}}
                                            {{--</label>--}}
                                            {{--<span class="input-icon">--}}
                                            {{--<input class="form-control" type="text" placeholder="Text Field">--}}
                                            {{--<i class="fa fa-linkedin"></i> </span>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label class="control-label">--}}
                                            {{--Skype--}}
                                            {{--</label>--}}
                                            {{--<span class="input-icon">--}}
                                            {{--<input class="form-control" type="text" placeholder="Text Field">--}}
                                            {{--<i class="fa fa-skype"></i> </span>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</fieldset>--}}
                                    <fieldset>
                                        <legend>
                                            User Role
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                @foreach($roles as $role)
                                                    <div class="checkbox clip-check check-primary checkbox-inline">
                                                        <input type="checkbox" id="{{$role->name}}" name="roles[]"
                                                               value="{{$role->id}}" @if(old('roles')!=null) @if(in_array($role->id,old('roles'))) checked @endif @endif>
                                                        <label for="{{$role->name}}">
                                                            {{$role->display_name}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                {{--<div class="checkbox clip-check check-primary checkbox-inline">--}}
                                                {{--<input type="checkbox" id="checkbox4" value="1" checked="">--}}
                                                {{--<label for="checkbox4">--}}
                                                {{--Checkbox 1--}}
                                                {{--</label>--}}
                                                {{--</div>--}}
                                                {{--<div class="checkbox clip-check check-primary checkbox-inline">--}}
                                                {{--<input type="checkbox" id="checkbox5" value="1">--}}
                                                {{--<label for="checkbox5">--}}
                                                {{--Checkbox 2--}}
                                                {{--</label>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            {{--<div class="row">--}}
                            {{--<div class="col-md-12">--}}
                            {{--<div>--}}
                            {{--Required Fields--}}
                            {{--<hr>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="row">--}}
                            {{--<div class="col-md-8">--}}
                            {{--<p>--}}
                            {{--By clicking UPDATE, you are agreeing to the Policy and Terms &amp; Conditions.--}}
                            {{--</p>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4">--}}
                            {{--<button class="btn btn-primary pull-right" type="submit">--}}
                            {{--Update <i class="fa fa-arrow-circle-right"></i>--}}
                            {{--</button>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</form>--}}
                        </div>
                        {{--<div id="panel_projects" class="tab-pane fade">--}}
                        {{--<div class="panel-body">--}}
                        {{--<div class="checkbox clip-check check-primary checkbox-inline">--}}
                        {{--<input type="checkbox" id="checkbox4" value="1" checked="">--}}
                        {{--<label for="checkbox4">--}}
                        {{--Checkbox 1--}}
                        {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="checkbox clip-check check-primary checkbox-inline">--}}
                        {{--<input type="checkbox" id="checkbox5" value="1">--}}
                        {{--<label for="checkbox5">--}}
                        {{--Checkbox 2--}}
                        {{--</label>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<table class="table" id="projects">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                        {{--<th>Project Name</th>--}}
                        {{--<th class="hidden-xs">Client</th>--}}
                        {{--<th>Proj Comp</th>--}}
                        {{--<th class="hidden-xs">%Comp</th>--}}
                        {{--<th class="hidden-xs center">Priority</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--<tr>--}}
                        {{--<td>IT Help Desk</td>--}}
                        {{--<td class="hidden-xs">Master Company</td>--}}
                        {{--<td>11 november 2014</td>--}}
                        {{--<td class="hidden-xs">--}}
                        {{--<div class="progress active progress-xs">--}}
                        {{--<div style="width: 70%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" role="progressbar" class="progress-bar progress-bar-warning">--}}
                        {{--<span class="sr-only"> 70% Complete (danger)</span>--}}
                        {{--</div>--}}
                        {{--</div></td>--}}
                        {{--<td class="center hidden-xs"><span class="label label-danger">Critical</span></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>PM New Product Dev</td>--}}
                        {{--<td class="hidden-xs">Brand Company</td>--}}
                        {{--<td>12 june 2014</td>--}}
                        {{--<td class="hidden-xs">--}}
                        {{--<div class="progress active progress-xs">--}}
                        {{--<div style="width: 40%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-info">--}}
                        {{--<span class="sr-only"> 40% Complete</span>--}}
                        {{--</div>--}}
                        {{--</div></td>--}}
                        {{--<td class="center hidden-xs"><span class="label label-warning">High</span></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>ClipTheme Web Site</td>--}}
                        {{--<td class="hidden-xs">Internal</td>--}}
                        {{--<td>11 november 2014</td>--}}
                        {{--<td class="hidden-xs">--}}
                        {{--<div class="progress active progress-xs">--}}
                        {{--<div style="width: 90%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-success">--}}
                        {{--<span class="sr-only"> 90% Complete</span>--}}
                        {{--</div>--}}
                        {{--</div></td>--}}
                        {{--<td class="center hidden-xs"><span class="label label-success">Normal</span></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Local Ad</td>--}}
                        {{--<td class="hidden-xs">UI Fab</td>--}}
                        {{--<td>15 april 2014</td>--}}
                        {{--<td class="hidden-xs">--}}
                        {{--<div class="progress active progress-xs">--}}
                        {{--<div style="width: 50%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" role="progressbar" class="progress-bar progress-bar-warning">--}}
                        {{--<span class="sr-only"> 50% Complete</span>--}}
                        {{--</div>--}}
                        {{--</div></td>--}}
                        {{--<td class="center hidden-xs"><span class="label label-success">Normal</span></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Design new theme</td>--}}
                        {{--<td class="hidden-xs">Internal</td>--}}
                        {{--<td>2 october 2014</td>--}}
                        {{--<td class="hidden-xs">--}}
                        {{--<div class="progress active progress-xs">--}}
                        {{--<div style="width: 20%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar" class="progress-bar progress-bar-success">--}}
                        {{--<span class="sr-only"> 20% Complete (warning)</span>--}}
                        {{--</div>--}}
                        {{--</div></td>--}}
                        {{--<td class="center hidden-xs"><span class="label label-danger">Critical</span></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>IT Help Desk</td>--}}
                        {{--<td class="hidden-xs">Designer TM</td>--}}
                        {{--<td>6 december 2014</td>--}}
                        {{--<td class="hidden-xs">--}}
                        {{--<div class="progress active progress-xs">--}}
                        {{--<div style="width: 40%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-warning">--}}
                        {{--<span class="sr-only"> 40% Complete (warning)</span>--}}
                        {{--</div>--}}
                        {{--</div></td>--}}
                        {{--<td class="center hidden-xs"><span class="label label-warning">High</span></td>--}}
                        {{--</tr>--}}
                        {{--</tbody>--}}
                        {{--</table>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: USER PROFILE -->
    {!! Form::close() !!}
@stop

@section('javascript')
    <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
    {{--    <script src="{{asset('assets/js/form-elements.js')}}"></script>--}}
    <script>
        jQuery(document).ready(function () {
//            FormElements.init();
//            $(".js-example-basic-single").select2();
//            $("#company_code").select2({
//                placeholder: "Company Code"
//            });
            $("#business_area").select2({
                placeholder: "Business Area"
            });
        });
    </script>

@stop