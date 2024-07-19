@extends('layout')

@section('css')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .thead-green {
            background: #1bb99a;
            color: #ffffff;
        }
        .thead-orange {
            background-color: #FAE8C4;
            color: #eaa211;
        }
    </style>
@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Feedback Bawahan</h4>
        </div>
    </div>
    <div class="card-box feedback">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-md-3 col-xl-2 col-xs-4 align-center">
                        <img src="{{ app_user_avatar($dataAtasan->nip) }}"
                             alt="user"
                             width="200"
                             class="radius-full img-fluid mx-auto d-block">

                    </div>
                    <div class="col-md-9 col-xl-10 col-xs-8 word-break">
                        <button class="btn btn-blue-2">ATASAN YANG DINILAI</button>
                        <div class="name">
                            <em class="zmdi zmdi-account"></em>&nbsp; Nama Atasan : {{ $dataAtasan->name }}
                        </div>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <em class="zmdi zmdi-card"></em> &nbsp; <span class="text">NIP : {{ $dataAtasan->nip }}</span>
                            </div>
                            <div class="col-md-4">
                                <em class="zmdi zmdi-card"></em> &nbsp;<span class="text">JABATAN : {{ $dataAtasan->ad_title }}</span>
                            </div>
                            <div class="col-md-4">
                                <em class="zmdi zmdi-card"></em> &nbsp; <span class="text">KANTOR : {{ $dataAtasan->business_area }} - {{ $dataAtasan->ad_company }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    @include('components.flash')
    <form action="{{ route('feedback.update', $feedback) }}" method="post" id="feedback">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="row">
            <div class="col-sm-12">
                <div class="comp-tab-blue-2">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">1. {!! $kelebihan !!}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">2. {!! $kekurangan !!}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">3. Harapan dan {!! $saran !!}</a>
                        </li>
                    </ul>
                    <div class="tab-content comp-tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-12 lh-35">
                                            <div class="alert alert-success" style="color: #606060 !important">
                                                <span class="text-danger">*</span> Silahkan pilih 3 {!! $kelebihan !!} yang menonjol pada atasan Anda
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-box border-blue">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalKelebihan"><em class="fa fa-plus"></em>
                                            Pilih {!! $kelebihan !!}
                                        </button>

                                        <br>

                                        <table id="selectedKelebihan" class="table table-striped">
                                            @foreach($selectedKelebihan as $id => $label)
                                                <tr>
                                                    <td>
                                                        {{ $label }}
                                                        <input type="hidden" class="terpilihKelebihan" value="{{ $id }}">
                                                    </td>
                                                    <td>{{ $feedback->alasan_kelebihan[$id] }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 pull-right">
                                    <div class="button-list">
                                        <button type="button" class="btnNextTab btn btn-primary btn-lg pull-right"><em class="fa fa-arrow-circle-right"></em>
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-12 lh-35">
                                            <div class="alert alert-warning" style="color: #606060 !important">
                                                <span class="text-danger">*</span> Silahkan pilih 3 {!! $kekurangan !!} yang perlu ditingkatkan dari atasan Anda
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-box border-blue">
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalKekurangan"><em class="fa fa-plus"></em>
                                            Pilih {!! $kekurangan !!}
                                        </button>

                                        <br>

                                        <table id="selectedKekurangan" class="table table-striped">
                                            @foreach($selectedKekurangan as $id => $label)
                                                <tr>
                                                    <td>
                                                        {{ $label }}
                                                        <input type="hidden" class="terpilihKekurangan" value="{{ $id }}">
                                                    </td>
                                                    <td>{{ $feedback->alasan_kekurangan[$id] }}</td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 pull-right">
                                    <div class="button-list">
                                        <button type="button" class="btnNextTab btn btn-primary btn-lg pull-right"><em class="fa fa-arrow-circle-right"></em>
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pernr_leader" class="form-control-label">
                                            Harapan <span class="text-danger">*</span></label>
                                        <div class="pad-l-8 min-height-50"><i>Harapan (jangka panjang) adalah perubahan apa yang diinginkan pada Leader</i></div>
                                        <div>
                                        <textarea name="harapan" rows="10"
                                                  class="form-control form-control-danger deskripsi"
                                                  placeholder="Masukan Deskripsi Kelebihan">{!! $feedback->harapan !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pernr_leader" class="form-control-label">
                                        {!! $saran !!} <span class="text-danger">*</span></label>
                                        <div class="pad-l-8 min-height-50"><i>{!! $saran !!} (jangka dekat) adalah masukan kepada leader untuk mewujudkan harapan tersebut</i></div>
                                        <div>
                                        <textarea name="saran" rows="10"
                                                  class="form-control form-control-danger deskripsi"
                                                  placeholder="Masukan Deskripsi Kelebihan">{!! $feedback->saran !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 pull-right">
                                    <div class="button-list">
                                        <a href="{{ route('dashboard-bawahan.liquid-jadwal.index') }}" class="btn btn-warning btn-lg pull-right"><em class="fa fa-times"></em>
                                            Cancel
                                        </a>
                                        <button id="submit-forms" type="submit" class="btn btn-primary btn-lg pull-right"><em class="fa fa-floppy-o"></em>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('liquid.feedback._modal-kelebihan')
        @include('liquid.feedback._modal-kekurangan')
    </form>
@stop

@section('javascript')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.16/sorting/custom-data-source/dom-checkbox.js"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/app/kelebihanKekuranganSelector.js?_').filemtime(public_path('assets/plugins/app/kelebihanKekuranganSelector.js')) }}"></script>

    <script>
        $(document).ready(function () {
            const COUNT_KK = 3;
            const wordCount = {{ $wordCount }};

            $('#modalKelebihan').kelebihanKekuranganSelector({
                target: $('#selectedKelebihan'),
                min: COUNT_KK,
                max: COUNT_KK,
                labelMessage: "{!! $kelebihan !!}",
                wordCount: wordCount,
            });
            $('#modalKekurangan').kelebihanKekuranganSelector({
                target: $('#selectedKekurangan'),
                min: COUNT_KK,
                max: COUNT_KK,
                labelMessage: "{!! $kekurangan !!}",
                wordCount: wordCount,
            });

            // INITIATE TINYMCE
            tinymce.init({
                mode: "textareas",
                editor_selector: "deskripsi",
                height: 200,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            });

            // VALIDATE WITH SWAL BEFORE SUBMIT
            $('#submit-forms').click(function(e){
                let invalidKelebihanCount = $('#selectedKelebihan tr').length != COUNT_KK;
                let invalidKekuranganCount = $('#selectedKekurangan tr').length != COUNT_KK;
                let invalidHarapan = tinymce.get('harapan').getContent() === "";
                let invalidSaran = tinymce.get('saran').getContent() === "";

                var errorMessage = null;
                if (invalidKelebihanCount) {
                    errorMessage = "Silakan pilih "+COUNT_KK+" {!! $kelebihan !!}";
                } else if (invalidKekuranganCount) {
                    errorMessage = "Silakan pilih "+COUNT_KK+" {!! $kekurangan !!}";
                } else if (invalidHarapan || invalidSaran) {
                    errorMessage = "Silakan isi Harapan dan Saran";
                }

                if (errorMessage != null) {
                    e.preventDefault();
                    let swalOpt = {
                        title: "Warning!",
                        text: errorMessage,
                        type: "warning",
                        showCancelButton: false,
                        cancelButtonClass: 'btn-secondary waves-effect',
                        confirmButtonClass: 'btn-primary waves-effect waves-light',
                        confirmButtonText: 'OK',
                    }
                    swal(swalOpt);
                }
            })

            // PREV NEXT TAB Navigation
            $('.btnNextTab').click(function(){
                $('.nav-tabs  .active').parent().next('li').find('a').trigger('click');
            });
        });
        function actDisableAlasanKelebihan(id){
            var check = $(`#boxes_kelebihan_${id}`).is(':not(:checked)');
            $(`#texts_area_kelebihan_${id}`).prop('disabled', $(`#boxes_kelebihan_${id}`).is(':not(:checked)'));
            if(check){
                $(`textarea#texts_area_kelebihan_${id}`).val('');
            }else{
                $(`#texts_area_kekurangan_${id}`).val('');
                $(`#texts_area_kekurangan_${id}`).prop('disabled', true);

                $(`#boxes_kekurangan_${id}`).prop('checked', false);
                $(`#boxes_kekurangan_${id}`).prop('disabled', true);
            }
        }
        function actDisableAlasanKekurangan(id){
            var check = $(`#boxes_kekurangan_${id}`).is(':not(:checked)');
            $(`#texts_area_kekurangan_${id}`).prop('disabled', $(`#boxes_kekurangan_${id}`).is(':not(:checked)'));
            if(check){
                $(`textarea#texts_area_kekurangan_${id}`).val('');
            }else{
                $(`#texts_area_kelebihan_${id}`).val('');
                $(`#texts_area_kelebihan_${id}`).prop('disabled', true);

                $(`#boxes_kelebihan_${id}`).prop('checked', false);
                $(`#boxes_kelebihan_${id}`).prop('disabled', true);
            }
        }
    </script>
@stop
