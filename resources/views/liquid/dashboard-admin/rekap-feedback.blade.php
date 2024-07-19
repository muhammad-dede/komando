@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .select2 {
            width: 100% !important;
        }
        div.table-responsive>div.dataTables_wrapper>div.row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            column-span: all;
        }
        .datatable.table {
            width: 100% !important;
        }
    </style>
@stop

@section('title')
    @include(
        'components.form-filter-report-liquid',
        [
            'title' => 'Rekap Feedback',
            'downloadUrl' => route('dashboard-admin.rekap-feedback.download', request()->only('periode', 'company_code', 'unit_code'))
        ]
    )
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12">
            <div class="card-box width-full">
                @include('liquid.dashboard-admin._nav-report')
                <br>
                <div class="table-responsive">
                    <table class="datatable table table-striped table-bordered">
                        <thead class="thead-blue">
                        <tr>
                            <th class="color-white vertical-middle" style="min-width: 100px;">Unit</th>
                            <th class="color-white vertical-middle" style="min-width: 100px;">Atasan</th>
                            <th class="color-white vertical-middle">NIP</th>
                            <th class="color-white vertical-middle" style="min-width: 100px;">Jabatan</th>
                            <th class="color-white vertical-middle" style="min-width: 150px;">Kelebihan Lainnya</th>
                            <th class="color-white vertical-middle" style="min-width: 150px;">Kekurangan Lainnya</th>
                            <th class="color-white vertical-middle" style="min-width: 150px;">Harapan</th>
                            <th class="color-white vertical-middle" style="min-width: 150px;">Saran</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
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
                columns: {!! json_encode($datatable->getColumns()) !!}
			});
        });
    </script>
@stop
