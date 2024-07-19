@extends('layout')

@section('css')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.css') }}">
    <style>
		.slider-selection {
			background: #0099FA !important;
		}
		.slider.slider-horizontal {
			width: 100% !important;
			height: 20px;
		}
		.slider-handle {
			background-color: #fff !important;
			background-image: none !important;
			-webkit-box-shadow: 1px 1px 24px -2px rgba(0,0,0,0.75) !important;
			-moz-box-shadow: 1px 1px 24px -2px rgba(0,0,0,0.75) !important;
			box-shadow: 1px 1px 24px -2px rgba(0,0,0,0.75) !important;
		}
		.slider .tooltip.top {
			margin-top: -21px;
			padding: 0px;
			font-size: 9px;
		}
		.tooltip-inner {
			border-radius: 100px !important;
			height: 20px;
			border: none;
			background-color: #0099FA !IMPORTANT;
			width: 20px;
			padding: 3px 8px;
		}
        .thead-green {
            background: #1bb99a;
            color: #ffffff;
        }
        .thead-orange {
            background: #FAE8C4;
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
    <form action="{{ route('feedback.store').'?liquid_peserta_id='.request()->liquid_peserta_id }}" method="post" id="feedback">
        {{ csrf_field() }}
        <input type="hidden" name="survey_question_detail_id">
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
                                                <span class="text-danger">*</span> Silahkan pilih 3 <strong>{!! $kelebihan !!}</strong> yang menonjol pada atasan Anda
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-box border-blue">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalKelebihan"><em class="fa fa-plus"></em>
                                            Pilih {!! $kelebihan !!}
                                        </button>
                                        <br>

                                        <table id="selectedKelebihan" class="table table-striped" style="margin-top: 14px"></table>
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
                                                <span class="text-danger">*</span> Silahkan pilih 3 <strong>{!! $kekurangan !!}</strong> yang perlu ditingkatkan dari atasan Anda
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-box border-blue">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalKekurangan"><em class="fa fa-plus"></em>
                                            Pilih {!! $kekurangan !!}
                                        </button>
                                        <br>

                                        <table id="selectedKekurangan" class="table table-striped" style="margin-top: 14px"></table>
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
                                                placeholder="Masukan Deskripsi Kelebihan">
                                            </textarea>
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
                                                placeholder="Masukan Deskripsi Kelebihan">
                                            </textarea>
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

    <!-- Modal -->
    <div class="modal fade" id="modal-survey-question" tabindex="-1" role="dialog" aria-labelledby="modal-survey-question" aria-hidden="false" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg vertical-align-center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="#">Survey</h6>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Survey</th>
                                <th class="align-center" width="30%">Skor <em class="fa fa-star color-star"></em></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ (empty($surveyQuestion->question)) ? "Question tidak ditemukan." : $surveyQuestion->question }}</td>
                                <td style="padding: 1rem 2rem">
                                    <input class="input-range slider-primary" name="set_answer" data-slider-id='ex1Slider' type="text" data-slider-min="0"
                                        data-slider-tooltip="always" data-slider-max="10" data-slider-step="1"
                                        data-slider-value="0" value=""/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                Alasan: <span class="text-danger">*</span></label>
                                <textarea id="text_area_reason" rows="2"
                                    class="form-control form-control-danger reason-answare"
                                    placeholder="Masukan Alasan Anda"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!--end modal-body-->
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Tidak</button> -->
                    @if(!empty($surveyQuestion->question))
                        <input type="hidden" name="survey_question_id" value="{{ (empty($surveyQuestion->id)) ? '' : $surveyQuestion->id }}">
                        <input type="hidden" name="liquids_id" value="{{ request()->liquid_id }}">
                        <input type="hidden" name="liquid_peserta_id" value="{{ request()->liquid_peserta_id }}">
                        <input type="hidden" name="answer">
                        <button type="button" class="btn btn-primary btn-sm" id="save_survey">Simpan</button>
                    @endif
                </div><!--end modal-footer-->
            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div>
    <!--end modal-->
@stop

@section('javascript')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.16/sorting/custom-data-source/dom-checkbox.js"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/app/kelebihanKekuranganSelector.js?_').filemtime(public_path('assets/plugins/app/kelebihanKekuranganSelector.js')) }}"></script>
    <script src="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $(`#modal-survey-question`).modal('show');
            $( 'input[name=set_answer]').each(function(){
				var value = $(this).attr('data-slider-value');
				var separator = value.indexOf(',');
				if( separator !== -1 ){
					value = value.split(',');
					value.forEach(function(item, i, arr) {
						arr[ i ] = parseFloat( item );
					});
				} else {
					value = parseFloat( value );
				}
				$( this ).slider({
					formatter: function(value) {
						return value;
					},
					min: parseFloat( $( this ).attr('data-slider-min') ),
					max: parseFloat( $( this ).attr('data-slider-max') ),
					range: $( this ).attr('data-slider-range'),
					value: value,
					tooltip_split: $( this ).attr('data-slider-tooltip_split'),
					tooltip: $( this ).attr('data-slider-tooltip')
				});
			});
            $(`input[name=set_answer]`).change(function() {
                var value = $(this).val();
                $(`input[name=answer]`).val(value);

                $('#save_survey').prop('disabled', false);
            });
            $(`#save_survey`).prop('disabled', true);
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
                let invalidKelebihanCount = $('[name="boxes_kelebihan[]"]:checked').length != COUNT_KK;
                let invalidKekuranganCount = $('[name="boxes_kekurangan[]"]:checked').length != COUNT_KK;
                let invalidHarapan = tinymce.get('harapan').getContent() === "";
                let invalidSaran = tinymce.get('saran').getContent() === "";

                var errorMessage = null;
                if (invalidKelebihanCount) {
                    errorMessage = "Silakan pilih "+COUNT_KK+" {!! $kelebihan !!}";
                } else if (invalidKekuranganCount) {
                    errorMessage = "Silakan pilih "+COUNT_KK+" {!! $kekurangan !!}";
                } else if (invalidHarapan || invalidSaran) {
                    errorMessage = "Silakan isi Harapan dan {!! $saran !!}";
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

            $('.spell-checking').on('change', function () {
                let elm = $(this),
                    value = elm.val();

                // $.ajax({
                //     type: 'GET',
                //     url: '/api/spell-checker?words=' + value,
                //     success: function (res) {
                //         if (! res.isValid) {
                //             elm.parent().find('.spell-invalid').show();
                //             elm.parent().find('.spell-invalid.checked').text(res.spell);
                //         } else {
                //             elm.parent().find('.spell-invalid').hide();
                //         }
                //     }
                // })
            });
            $('button#save_survey').on('click', function(){
                saveSurvey();
            })
        });

        function saveSurvey() {
            var reason = $(`#text_area_reason`).val();
            if(reason.trim().length < 1) {
                swal("Error!", "Alasan Harus diisi", "error")
            }else{
                let datas = {};
                datas["survey_question_id"] = $(`input[name=survey_question_id]`).val();
                datas["liquids_id"] = $(`input[name=liquids_id]`).val();
                datas["liquid_peserta_id"] = $(`input[name=liquid_peserta_id]`).val();
                datas["answer"] = $(`input[name=answer]`).val();
                datas["reason"] = reason;
                datas["_token"] = '{{ csrf_token() }}';
                // console.log(datas)
                $.ajax({
                    url: "{{ url('feedback/save-survey') }}",
                    type: "POST",
                    dataType: "json",
                    data: datas,
                    success: function(data) {
                        if(data.status){
                            $(`input[name=survey_question_detail_id]`).val(data.survey_question_detail_id);
                            $(`#modal-survey-question`).modal('hide');
                            swal("Success!", "Data Berhasil Disimpan", "success")
                        } else {
                            swal("Error!", "Data Gagal Disimpan", "error")
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
        }

        function actDisableAlasanKelebihan(id){
            var check = $(`#boxes_kelebihan_${id}`).is(':not(:checked)');
            $(`#texts_area_kelebihan_${id}`).prop('disabled', $(`#boxes_kelebihan_${id}`).is(':not(:checked)'));
            if(check){
                $(`textarea#texts_area_kelebihan_${id}`).val('');
                $(`#boxes_kekurangan_${id}`).prop('disabled', false);
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
                $(`#boxes_kelebihan_${id}`).prop('disabled', false);
            }else{
                $(`#texts_area_kelebihan_${id}`).val('');
                $(`#texts_area_kelebihan_${id}`).prop('disabled', true);

                $(`#boxes_kelebihan_${id}`).prop('checked', false);
                $(`#boxes_kelebihan_${id}`).prop('disabled', true);
            }
        }
    </script>
@stop
