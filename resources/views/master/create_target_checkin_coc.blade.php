@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Create Target Persentase Check-in CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'master-data/target-checkin-coc/create']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit_divisi">Unit / Divisi</label>
                            <input type="text" class="form-control" id="unit_divisi" name="nama_unit" placeholder="Enter unit/division" value="{{ old('nama_unit') }}">
                        </div>
                        <div class="form-group">
                            <label for="kode_organisasi">Kode Organisasi SAP</label>
                            <input type="text" class="form-control" id="kode_organisasi" name="orgeh" placeholder="Enter organization code" value="{{ old('orgeh') }}">
                        </div>
                        <div class="form-group">
                            <label for="company_code">Company Code</label>
                            <input type="text" class="form-control" id="company_code" name="company_code" placeholder="Enter company code" value="{{ old('company_code') }}">
                        </div>
                        <div class="form-group">
                            <label for="target">Target (%)</label>
                            <input type="number" class="form-control" id="target" name="target" placeholder="Enter target" value="{{ old('target') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 pull-left">
                        <div class="button-list">
                            <button type="submit" class="btn btn-primary btn-lg pull-left"><i class="fa fa-save"></i>
                                Save
                            </button>
                            <button type="button" class="btn btn-warning btn-lg pull-left" onclick="window.location.href='{{ url('/master-data/target-checkin-coc') }}';"><i class="fa fa-times"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
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
            $("#tahun").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable();
        });

    </script>

@stop