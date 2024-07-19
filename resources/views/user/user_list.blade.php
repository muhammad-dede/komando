@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    @include('components.filter.user-management', ['title' => 'User Management'])
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="col-md-12">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="center"></th>
                                <th class="center">Username</th>
                                <th class="center">NIP</th>
                                <th class="center hidden-xs">Name</th>
                                <th class="center">Email</th>
                                <th class="center hidden-xs">Company</th>
                                <th class="center">Company Code</th>
                                <th class="center hidden-xs">Business Area</th>
                                <th class="center">Role</th>
                                <th class="center">Action</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
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
        $(document).ready(function () {
            $("#business_area").select2();
            $("#filter-role").select2();

            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: {!! json_encode($datatable->getColumns()) !!},
                order: [[1, "asc"]]
            });
        });

    </script>

@stop
