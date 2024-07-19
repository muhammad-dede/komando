@extends('layout')

@section('css')
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" media="screen">
    <link href="{{asset('vendor/DataTables/css/DT_bootstrap.css')}}" rel="stylesheet" media="screen">
@stop

@section('title')
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">Role List</h1>
            <span class="mainDescription">display user role</span>
        </div>
        <ol class="breadcrumb">
            <li>
                <span>User Management</span>
            </li>
            <li class="active">
                <span>Role</span>
            </li>
        </ol>
    </div>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-sm-12 space20">
                <a href="{{url('user-management/role/create')}}" class="btn btn-wide btn-primary"><i class="fa fa-plus"></i> Add New Role</a>
            </div>
            <div class="col-md-12">
                {{--<h5 class="over-title margin-bottom-15">Basic <span class="text-bold">Data Table</span></h5>--}}
                {{--<p>--}}
                    {{--DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, and will add advanced interaction controls to any HTML table.--}}
                {{--</p>--}}
                <table class="table table-striped table-bordered table-hover table-full-width" id="table_role">
                    <thead>
                    <tr>
                        <th class="center">ID</th>
                        <th class="center">System Name</th>
                        <th class="center hidden-xs">Display Name</th>
                        <th class="center">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{$role->id}}</td>
                        <td><a href="{{url('user-management/role/'.$role->id)}}">{{$role->name}}</a></td>
                        <td class="hidden-xs">{{$role->display_name}}</td>
                        <td>{{$role->description}}</td>

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