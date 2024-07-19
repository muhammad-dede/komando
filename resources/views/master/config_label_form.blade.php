@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-dismissable alert-danger">
            <strong>Failed!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <br>
        </div>
    @endif
    <div class="row mar-b-1rem mar-65-md">
        <div class="col-md-5">
            <h4 class="page-title">{{ $title }} Konfigurasi label</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="card-box table-responsive comp-tab-blue">
                <form action="{{ $action }}" method="post" id="form_kk">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            @if(!empty($edit))
                                {{ method_field('PATCH') }}
                            @endif
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="form-control-label">Status <span class="text-danger">*</span></div>
                                <select class="form-control form-control-danger" name="status" required>
                                    @if(!empty($edit))
                                        <option value="{{ \App\Enum\ConfigLabelEnum::TIDAK_AKTIF }}" {{ ($edit->status != "1") ? "selected" : "" }}>Tidak Aktif</option>
                                        <option value="{{ \App\Enum\ConfigLabelEnum::AKTIF }}" {{ ($edit->status == "1") ? "selected" : "" }}>Aktif</option>
                                    @else
                                        <option value="{{ \App\Enum\ConfigLabelEnum::TIDAK_AKTIF }}">Tidak Aktif</option>
                                        <option value="{{ \App\Enum\ConfigLabelEnum::AKTIF }}">Aktif</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="form-control-label">Key <span class="text-danger">*</span></div>
                                <select class="form-control form-control-danger" name="keys" required>
                                    @if(!empty($edit))
                                        <option value="{{ \App\Enum\ConfigLabelEnum::KEY_KELEBIHAN }}" {{ ($edit->keys == "kelebihan") ? "selected" : "" }}>
                                                {{ \App\Enum\ConfigLabelEnum::KEY_KELEBIHAN }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_KEKURANGAN }}" {{ ($edit->keys == "kekurangan") ? "selected" : "" }}>
                                                {{ \App\Enum\ConfigLabelEnum::KEY_KEKURANGAN }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_SARAN }}" {{ ($edit->keys == "saran") ? "selected" : "" }}>
                                                {{ \App\Enum\ConfigLabelEnum::KEY_SARAN }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_INDIKATOR }}" {{ ($edit->keys == "indikator") ? "selected" : "" }}>
                                                {{ \App\Enum\ConfigLabelEnum::KEY_INDIKATOR }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_1 }}" {{ ($edit->keys == "form_penilaian_1") ? "selected" : "" }}>
                                                {{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_1 }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_2 }}" {{ ($edit->keys == "form_penilaian_2") ? "selected" : "" }}>
                                                {{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_2 }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_USULAN_ATASAN }}" {{ ($edit->keys == "usulan_atasan") ? "selected" : "" }}>
                                                {{ \App\Enum\ConfigLabelEnum::KEY_USULAN_ATASAN }}
                                        </option>
                                    @else
                                        <option value="{{ \App\Enum\ConfigLabelEnum::KEY_KELEBIHAN }}">
                                                {{ \App\Enum\ConfigLabelEnum::KEY_KELEBIHAN }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_KEKURANGAN }}">
                                                {{ \App\Enum\ConfigLabelEnum::KEY_KEKURANGAN }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_SARAN }}">
                                                {{ \App\Enum\ConfigLabelEnum::KEY_SARAN }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_INDIKATOR }}">
                                                {{ \App\Enum\ConfigLabelEnum::KEY_INDIKATOR }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_1 }}">
                                                {{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_1 }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_2 }}">
                                                {{ \App\Enum\ConfigLabelEnum::KEY_FORM_PENILAIAN_2 }}
                                        </option>
                                        <option
                                            value="{{ \App\Enum\ConfigLabelEnum::KEY_USULAN_ATASAN }}">
                                                {{ \App\Enum\ConfigLabelEnum::KEY_USULAN_ATASAN }}
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label">Translasi <span class="text-danger">*</span></label>
                                <input type="text" name="translasi" class="form-control" placeholder="Translasi" value="{{ (empty($edit->translasi)) ? '' : $edit->translasi }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Sort Translasi <span class="text-danger">*</span></label>
                                <input type="text" name="sort_translasi" class="form-control" placeholder="Translasi" value="{{ (empty($edit->sort_translasi)) ? '' : $edit->sort_translasi }}" required>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="lh-70 float-right">
                                <a href="{{ url('master-data/config-label') }}" class="btn btn-warning">
                                    <em class="fa fa-times"></em>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <em class="fa fa-floppy-o"></em>
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $("select[name=status]").select2();
            $("select[name=keys]").select2();
        });
    </script>
@endsection