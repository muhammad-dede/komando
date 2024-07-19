@extends('layout')

@section('css')
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" media="screen">
    <link href="{{asset('vendor/DataTables/css/DT_bootstrap.css')}}" rel="stylesheet" media="screen">
@stop

@section('title')
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">User List</h1>
            <span class="mainDescription">display registered users</span>
        </div>
        <ol class="breadcrumb">
            <li>
                <span>User Management</span>
            </li>
            <li class="active">
                <span>User</span>
            </li>
        </ol>
    </div>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-sm-12 space20">
                <a href="{{url('user-management/user/create')}}" class="btn btn-wide btn-primary"><i class="fa fa-plus"></i> Add New User</a>
            </div>
            <div class="col-md-12">
                {{--<h5 class="over-title margin-bottom-15">Basic <span class="text-bold">Data Table</span></h5>--}}
                {{--<p>--}}
                    {{--DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, and will add advanced interaction controls to any HTML table.--}}
                {{--</p>--}}
                <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
                    <thead>
                    <tr>
                        <th class="center"></th>
                        <th class="center">Username</th>
                        <th class="center hidden-xs">Name</th>
                        <th class="center">Email</th>
                        <th class="center hidden-xs">Domain</th>
                        <th class="center">Company Code</th>
                        <th class="center hidden-xs">Business Area</th>
                        <th class="center">Role</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($userlist as $user)
                    <tr>
                        <td><a href="{{url('user-management/user/'.$user->id)}}"><img src="{{url('/')}}/assets/images/user.jpg" alt="User" class="img-thumbnail" width="32"></a></td>
                        <td><a href="{{url('user-management/user/'.$user->id)}}">{{$user->username}}</a></td>
                        <td class="hidden-xs">{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->domain}}</td>
                        <td>@if($user->company_code!=null){{$user->company_code}} - {{$user->companyCode->description}}@endif</td>
                        <td class="hidden-xs">@if($user->business_area!=null){{$user->business_area}} - {{$user->businessArea->description}}@endif</td>
                        <td>
                            @if($user->roles->count()>0)
                                @foreach($user->roles as $role)
                                    <span class="label label-sm label-info">{{$role->display_name}}</span>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('vendor/DataTables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/table-data.js')}}"></script>
    <script>
        jQuery(document).ready(function() {
            TableData.init();
        });
    </script>
@stop