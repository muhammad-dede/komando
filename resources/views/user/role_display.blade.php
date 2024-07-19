@extends('layout')

@section('css')
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" media="screen">
@stop

@section('title')
    {{--<h1 class="mainTitle">User Profile</h1>--}}
    {{--<span class="mainDescription">display your personal information</span>--}}
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">Display Role</h1>
            <span class="mainDescription">show user role</span>
        </div>
        <ol class="breadcrumb">
            <li>
                <span>User Management</span>
            </li>
            <li>
                <span><a href="{{url('user-management/role')}}">Role</a></span>
            </li>
            <li class="active">
                <span>Display</span>
            </li>
        </ol>
    </div>
    @stop

    @section('content')
    {!! Form::open(['url'=>'user-management/role/'.$role->id]) !!}
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
                    {!! Form::text('id_role', str_pad($role->id,10,'0', STR_PAD_LEFT), ['class'=>'form-control underline', 'readonly'=>'', 'id'=>'id_role']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::text('system_status', 'ACTV', ['class'=>'form-control underline', 'readonly'=>'']) !!}
                </div>
                <div class="col-sm-4">
                    <p>
                        <button type="submit" class="btn btn-wide btn-primary"><i class="fa fa-floppy-o"></i> Update
                        </button>
                        <a class="btn btn-wide btn-default" href="{{url('user-management/role')}}"><i
                                    class="fa fa-remove"></i> Cancel</a>

                    </p>
                </div>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12">

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
//            $("#business_area").select2({
//                placeholder: "Business Area"
//            });
        });
    </script>

@stop