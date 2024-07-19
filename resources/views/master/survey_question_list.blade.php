@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .text-center{
            text-align:center !important;
        }
    </style>
@stop

@section('title')
<div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">List Pertanyaan Survey</h4>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="lh-70 float-right">
				<form action="{{ url('master-data/survey-question/create') }}" method="get">
					<button type="submit"
						class="btn btn-blue"
						style="text-decoration: none">
						<em class="fa fa-plus"></em>
						Tambah Master Data
					</button>
				</form>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="datatable" class="table table-striped table-bordered">
                    <thead class="thead-blue">
                        <tr>
                            <th width="50" style="text-align: center">No.</th>
                            <th style="text-align: center">Pertanyaan</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">
        var dataTable;
        $(document).ready(function () {
            dataTable = $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                columns: [
                    { "data": "no", "class" : "text-center", "width":"5%", 'sortable' : false },
                    { "data": "question", 'sortable' : true},
                    { 
                        "data": "status",
                        "class" : "text-center", 
                        'sortable' : true,
                        'render': function (data, type, full, meta){
                            var tmp;
                            tmp = `<button class="btn btn-warning-small">${data}</button>`;
                            if(data == "Aktif"){
                                tmp = `<button class="btn btn-green-small">${data}</button>`;
                            }
                            return tmp;
                        }
                    },
                    {
                        'data': "id",
                        'searchable': false,
                        'orderable': false,
                        'width': '8%',
                        'sortable' : false,
                        'class': 'text-center',
                        'render': function (data, type, full, meta) {
                            var btn;
                            btn = `<a href="{{ url('master-data/survey-question/edit/${data}') }}"><em class="fa fa-pencil fa-2x"></em></a>`;
                            return btn;
                        }
                    },
                ],
                // "order" : [[1, 'desc']],
                "ajax": {
                    "url" : "{{ url('master-data/survey-question/list') }}",
                    "type" : "POST",
                    "data" : (d)=> {
                        d._token = '{{ csrf_token() }}'
                        d.filterName = $('input[name=filterNama]').val()
                        //d.filterStatus = $('select[name=filterStatus]').val()
                    }
                },
            })
        });

    </script>

@stop