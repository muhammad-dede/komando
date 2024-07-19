@push('styles')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .text-center{
            text-align:center !important;
        }
        .ui.search.dropdown.selection.multiple .ui.label {
            color: #606060;
        }

        .select2 {
            width: 100% !important;
        }

        i.icon.delete:before {
            content: '';
            background: url({{ asset('assets/images/clear.svg') }}) !important;
            background-size: cover !important;
            position: absolute;
            width: 15px;
            height: 15px;
            margin-left: 0px;
        }

        .ui.label > .close.icon, .ui.label > .delete.icon {
            cursor: pointer;
            margin-right: 0;
            padding-right: 5px;
            margin-left: .5em;
            font-size: .92857143em;
            opacity: .5;
            -webkit-transition: background .1s ease;
            transition: background .1s ease;
        }

        .ui.label > .icon {
            width: auto;
            margin: 0 .75em 0 0;
        }

        .ms-container#ms-my_multi_select3, .ms-container#ms-my_multi_select4 {
            max-width: 600px !important;
        }
    </style>
@endpush

@extends('layout')
@inject('liquidService', 'App\Services\LiquidService')

@section('title')
    <div class="row">
        <div class="lh-70 col-md-9 col-xs-12">
            @include('liquid.dashboard-atasan._title-page')
        </div>
    </div>
@stop

@section('content')
    <div class="row m-t-20 dashboard-admin-second-section">
        <div class="col-md-3">
            @include('liquid.dashboard-bawahan._side-menu')
        </div>
        <div class="col-md-9 pull-right">
            @include('liquid.dashboard-bawahan._table-add-atasan')
        </div>
    </div>
    @include('liquid.dashboard-bawahan._modal_add_atasan')
@stop


@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        var dataTable;
        $(document).ready(function () {
            dataTable = $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                columns: [
                    { 
                        "data": "snapshot_nip_atasan", 
                        "class" : "text-center",
                        "sortable" : false,
                        "render": function(data, type, full, meta) {
                            var url = "{{ app_user_avatar("+data+") }}"
                            var temp = `<img src="${url}"
                                alt="user"
                                width="200"
                                class="radius-full img-fluid mx-auto d-block">`;
                            return temp;
                        },
                    },
                    { "data": "snapshot_nama_atasan", "class" : "text-center", 'sortable' : false },
                    { "data": "snapshot_nip_atasan", "class" : "text-center", 'sortable' : false },
                    { "data": "snapshot_jabatan2_atasan", "class" : "text-center", 'sortable' : false },
                    { 
                        "data": "snapshot_unit_code", 
                        "class" : "text-center",
                        'sortable' : false,
                        "render": function(data, type, full, meta) {
                            var temp = `${data} - ${full.snapshot_unit_name }`;
                            return temp;
                        },
                    },
                    { 
                        "data": "feedback", 
                        "class" : "text-center", 
                        'sortable' : false,
                        "render": function(data, type, full, meta) {
                            var temp = `<span class="badge badge-danger">Belum diberikan feedback</span>`;
                            if(data){
                                temp = `<span class="badge badge-success">Feedback selesai</span>`;
                            }
                            return temp;
                        }
                    },
                ],
                // "order" : [[1, 'desc']],
                "ajax": {
                    "url" : "{{ url('dashboard-bawahan/table-add-atasan') }}",
                    "type" : "POST",
                    "data" : (d)=> {
                        d._token = '{{ csrf_token() }}'
                        d.idLiquid = '{{ $idLiquid }}'
                        //d.filterStatus = $('select[name=filterStatus]').val()
                    }
                },
            });

            $("#select_atasan").select2({
                dropdownParent: $("#modal_add_atasan"),
                multiple: false,
                placeholder: 'Search for a repository',
                minimumInputLength: 3,
                // minimumResultsForSearch: -1,
                ajax: {
                    url: "{{ url('api/pegawaiAtasan') }}",
                    dataType: 'json',
                    delay: 800,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            idLiquid: '{{ $idLiquid }}',
                            pernr: '{{ auth()->user()->strukturJabatan->pernr }}'
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    cache: true,
                }
            });
        });

        function saveAtasan() {
            var pilihAtasan = $(`#select_atasan`).find(':selected').val();
            if(pilihAtasan == ""){
                swal("Error!", "Atasan tidak boleh kosong", "error")
                return
            }
            let datas = {};
            datas["idLiquidPeserta"] = $(`#select_atasan`).find(':selected').val();
            datas["pernr"] = $(`input[name=pernr]`).val();
            datas["idLiquid"] = $(`input[name=id_liquid]`).val();
            datas["_token"] = '{{ csrf_token() }}';
            // console.log(datas)
            $.ajax({
                url: "{{ url('dashboard-bawahan/save-add-atasan') }}",
                type: "POST",
                dataType: "json",
                data: datas,
                success: function(data) {
                    if(data.status){
                        swal("Success!", "Data Berhasil Disimpan", "success")
                        dataTable.ajax.reload();
                    }else{
                        swal("Error!", data.msg, "error")
                    }
                    $("#select_atasan").select2("val", "");
                    $(`#modal_add_atasan`).modal('hide');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    </script>
@endpush
