@extends('layout')

@section('content')
    <div class="row">
        <div class="card-box width-full">
            @include('liquid.dashboard-admin._nav-report')

            <div class="table-responsive mar-t-1rem">
                <table class="datatable table table-striped table-bordered">
                    <thead class="thead-blue">
                        <tr>
                            <th class="color-white vertical-middle">NAMA</th>
                            <th class="color-white vertical-middle">NIP</th>
                            <th class="color-white vertical-middle">JUMLAH BAWAHAN</th>
                            <th class="color-white vertical-middle">JENJANG JABATAN</th>
                            <th class="color-white vertical-middle">SEBUTAN JABATAN</th>
                            <th class="color-white vertical-middle">UNIT</th>
                            @if(is_unit_pusat(request('unit_code', auth()->user()->getKodeUnit())))
                                <th class="color-white vertical-middle">DIVISI</th>
                            @endif
                            <th class="color-white vertical-middle">JUMLAH ACTIVITY LOG</th>
                            <th class="color-white vertical-middle">PENGUKURAN PERTAMA</th>
                            <th class="color-white vertical-middle">PENGUKURAN KEDUA</th>
                            <th class="color-white vertical-middle">AKSI</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .datatable.table {
            width: 100% !important;
        }
    </style>
@stop

@section('title')
    @include(
        'components.form-filter-report-liquid',
        [
            'title' => 'LIQUID Report Data',
            'downloadUrl' => route('dashboard-admin.liquid.download', isset($param) ? $param : request()->only('periode',
            'company_code', 'unit_code')),
        ]
    )
@stop

@section('javascript')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            var cols = {!! json_encode($datatable->getColumns()) !!};

            $('.datatable').DataTable({
                scrollY: 400,
                scrollX: true,
                processing: true,
                serverSide: true,
                bPaginate: true,
                ajax: {
                    url: window.location.href,
                    dataType: "json",
                    type: "GET"
                },
                columns: cols
            });
        });
    </script>
@stop
