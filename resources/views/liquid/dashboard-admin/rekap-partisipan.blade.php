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

    </style>
@stop

@section('title')
    @include(
        'components.form-filter-report-liquid',
        [
            'title' => 'Rekap Partisipan',
            'downloadUrl' => route('dashboard-admin.liquid-rekap-partisipan.download', request()->only('periode', 'company_code', 'unit_code'))
        ]
    )
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12">
            <div class="card-box width-full">
                @include('liquid.dashboard-admin._nav-report')
                <br>
                <div class="table-responsive mar-t-1rem">
                    <table class="datatable table table-striped table-bordered">
                        <thead class="thead-blue">
                        <tr>
                            <th class="color-white vertical-middle" >NO</th>
                            <th class="color-white vertical-middle" >COMPANY CODE</th>
                            <th class="color-white vertical-middle" >BUSINESS AREA</th>
                            <th class="color-white vertical-middle" >LEVEL</th>
                            <th class="color-white vertical-middle" >JUMLAH ATASAN</th>
                            <th class="color-white vertical-middle" >JUMLAH BAWAHAN</th>
                            <th class="color-white vertical-middle" >JUMLAH FEEDBACK</th>
                            <th class="color-white vertical-middle" >JUMLAH % PADA FASE FEEDBACK</th>
                            <th class="color-white vertical-middle" >JUMLAH PENYELARASAN</th>
                            <th class="color-white vertical-middle" >JUMLAH % PADA FASE PENYELARASAN</th>
                            <th class="color-white vertical-middle" >JUMLAH PENGUKURAN TAHAP 1</th>
                            <th class="color-white vertical-middle" >JUMLAH % PADA FASE PENGUKURAN TAHAP 1</th>
                            <th class="color-white vertical-middle" >JUMLAH ACTIVITY LOG</th>
                            <th class="color-white vertical-middle" >JUMLAH PENGUKURAN TAHAP 2</th>
                            <th class="color-white vertical-middle" >JUMLAH % PADA FASE PENGUKURAN TAHAP 2</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
            $(".select2").select2({
                templateResult: function (data, container) {
                    if (data.element) {
                        $(container).addClass($(data.element).attr("class"));
                    }
                    return data.text;
                }
            });

            $('select[name="company_code"]').on('change', function(e) {
                let company = $(e.currentTarget).val();
                if (company == '') {
                    $('select[name="unit_code"] option').removeClass('hidden');
                } else {
                    $('select[name="unit_code"] option').removeClass('hidden');
                    $('select[name="unit_code"] option').each(function (elm) {
                        if ($(this).data('company') == 'all') {
                            return true;
                        }

                        if ($(this).data('company') != company) {
                            $(this).addClass('hidden');
                        }
                    });
                    $('select[name="unit_code"]').val('').trigger('change');
                }
            });

            $('.datatable').DataTable({
                scrollY: 400,
                scrollX: true,
                processing: true,
                serverSide: true,
                bPaginate: true,
                ajax: {
                    url: "{{ route('data_table.rekap.partisipan') }}",
                    dataType: "json",
                    type: "GET",
                    data: function (d) {
                        let unitCode = $('select[name=unit_code]').val();
                        let companyCode = $('select[name=company_code]').val();
                        let periode = $('select[name=periode]').val();

                        d.unit_code = unitCode;
                        d.company_code = companyCode;

                        if (periode !== '') {
                            d.periode = periode;
                        }
                    }
                },
                columns: [
                    {"data":"rn"},
                    {"data":"company"},
                    {"data":"business"},
                    {"data":"levell"},
                    {"data":"jml_atasan"},
                    {"data":"jml_bawahan"},
                    {"data":"jml_feedback"},
                    {"data":"persen_feedback"},
                    {"data":"jml_penyelarasan"},
                    {"data":"persen_penyelarasan"},
                    {"data":"jml_pengukuran_pertama"},
                    {"data":"persen_pengukuran_pertama"},
                    {"data":"activity_log"},
                    {"data":"jml_pengukuran_kedua"},
                    {"data":"persen_pengukuran_kedua"}
                ]
            });
        });
    </script>
@stop
