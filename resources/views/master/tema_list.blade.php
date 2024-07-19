@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Tema CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="coc-tab" data-toggle="tab" href="#coc"
                           role="tab" aria-controls="coc" aria-expanded="true">Code of Conduct</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="master-tab" data-toggle="tab" href="#master"
                           role="tab" aria-controls="master">Master Data</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="coc"
                         aria-labelledby="coc-tab">
                        <table id="datatableCoc" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th style="text-align: center">Tema</th>
                                <th style="text-align: center">Start Date</th>
                                <th style="text-align: center">End Date</th>
                                <th style="text-align: center">Last Update by</th>
                                <th style="text-align: center">Updated at</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="master" role="tabpanel"
                         aria-labelledby="master-tab">
                        <div class="row m-b-20">
                            <div class="col-xl-12">
                                <div class="button-list">
                                    <a href="{{url('master-data/tema/create/')}}"
                                       class="btn btn-primary-outline btn-rounded waves-effect waves-light">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span>
                                        Create New
                                    </a>

                                </div>

                            </div>
                        </div>
                        <table id="datatableMaster" class="table table-striped table-bordered" width="100%">
                            <thead>
                            <tr>
                                <th style="text-align: center">Tema</th>
                                <th style="text-align: center" width="100">Jml. CoC</th>
                            </tr>
                            </thead>
                            <tbody>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatableCoc').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('master-data/tema') }}?tab=coc",
                columns: {!! json_encode($datatableCoc->getColumns()) !!},
            });
            $('#datatableMaster').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('master-data/tema') }}?tab=master",
                columns: {!! json_encode($datatableMaster->getColumns()) !!},
            });
        });

    </script>

@stop
