@extends('layout')

@push('styles')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Sweet Alert css -->
    <link href="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/light-gallery/dist/css/lightgallery.min.css') }}" />
@endpush


@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12 lh-70">
            <h4 class="page-title">Activity Log</h4>
        </div>
        <div class="col-md-6 col-xs-12 lh-70 align-right">
            <a href="{{ route('activity-log.create') }}" class="btn btn-primary"><em class="fa fa-plus"></em> Add Activity
                Log</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered kelebihan comp-list ">
                            <thead class="thead-blue">
                            <tr>
                                <th class="vertical-middle title-top color-white">Resolusi</th>
                                <th class="vertical-middle title-top color-white">Nama Kegiatan/Perbaikan</th>
                                <th class="vertical-middle title-top color-white">Tanggal Pelaksanaan</th>
                                <th class="vertical-middle title-top color-white">Tempat Pelaksanaan</th>
                                <th class="vertical-middle title-top color-white">Deskripsi Kegiatan</th>
                                <th class="vertical-middle title-top color-white align-center width-100">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($actLogBook as $item)
                                    <tr>
                                        <td>
                                            <ul>
                                                @foreach ($item->getResolusi() as $res)
                                                    <li>{{ $res }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $item->nama_kegiatan }}</td>
                                        <td>
                                            <div> <strong>{{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}</strong> </div>
                                            s/d
                                            <div> <strong>{{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}</strong> </div>
                                        </td>
                                        <td>{{ $item->tempat_kegiatan }}</td>
                                        <td>{!! $item->keterangan !!}</td>

                                        <td align="center">
                                            <div class="display-flex">
                                                <a class="btn btn-primary margin-rl-5" data-toggle="modal"
                                                    data-target="#activity{{ $item->id }}">
                                                    <em class="fa fa-eye" data-toggle="tooltip"
                                                        title="detail">
                                                    </em>
                                                </a>

                                                @include('liquid.activity-log._modal-detail', ['item' => $item])
                                                <div id="wrapper-galeri-{{ $item->id }}"></div>

                                                <a href="{{ route('activity-log.edit', $item->id) }}" class="btn btn-warning margin-rl-5"
                                                    data-toggle="tooltip" title="edit">
                                                    <em class="fa fa-pencil">
                                                    </em>
                                                </a>
                                                <form action="{{ route('activity-log.destroy', $item->id) }}" method="post" class="margin-rl-5">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button class="btn btn-danger hapus" data-toggle="tooltip" title="hapus">
                                                        <em class="fa fa-trash-o"></em>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Sweet Alert js -->
    <script src="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
    <script src="{{ asset('vendor/light-gallery/dist/js/lightgallery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $(".hapus").click(function () {
                swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this data!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            swal("Deleted!", "Your data has been deleted.", "success");
                        } else {
                            swal("Cancelled", "Your data is safe :)", "error");
                        }
                    });

            });
            $('#datatable').DataTable();

            var gallery = [];
            @foreach($actLogBook as $item)
                gallery[{{ $item->id }}] = {!! json_encode($item->presentLightGallery()) !!};
            @endforeach

            $(".show-galeri-kegiatan").click(function () {
                $('#'+$(this).data('modal')).modal('hide');
                $('#'+$(this).data('galeri')).lightGallery({
                    dynamic: true,
                    dynamicEl: gallery[$(this).data('id')]
                })
            });

        });
    </script>
@endpush
