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
            <h4 class="page-title">Tambah Data {!! $kelebihan !!} dan {!! $kekurangan !!}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="card-box table-responsive comp-tab-blue">
                <form action="{{ route('master-data.kelebihan-kekurangan.store') }}" method="post" id="form_kk">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <input type="hidden" name="index_kelebihan">
                            <input type="hidden" name="index_kekurangan">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="form-control-label">Judul {!! $kelebihan !!} dan {!! $kekurangan !!} <span
                                            class="text-danger">*</span></label>
                                <input type="text" name="judul_kk" class="form-control"
                                       placeholder="Tulis Judul" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Deskripsi <span
                                            class="text-danger">*</span></label>
                                <div>
										<textarea name="deskripsi_kk" rows="20"
                                                  class="form-control form-control-danger deskripsi mar-r-1rem"
                                                  placeholder="Masukan Deskripsi Program"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-control-label">Status <span
                                            class="text-danger">*</span></div>
                                <select class="select2 form-control form-control-danger" name="status"
                                        tabindex="-1" aria-hidden="true" required>
									<option
										value="{{ \App\Enum\KelebihanKekuranganStatus::AKTIF }}">
											Aktif
									</option>
									<option
										value="{{ \App\Enum\KelebihanKekuranganStatus::TIDAK_AKTIF }}" selected="selected">
											Tidak Aktif
									</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xl-12 wrapper-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-blue">
                                        <div class="form-group title-top color-white">
                                            <label>Kategori</label>

                                            <div>
                                                {!!
                                                    Form::select(
                                                        'category_1',
                                                        $categories,
                                                        null,
                                                        [
                                                            'class'=>'form-control select2',
                                                            'required' => true,
                                                        ]
                                                    )
                                                !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-1 col-xs-1">
                                                            <span class="badge badge-primary numbering float-right">1</span>
                                                        </div>
                                                        <div class="col-md-10 col-xs-11">
                                                            <div class="title-top mar-b-1rem">{!! $kelebihan !!}</div>
                                                            <div class="form-group">
                                                                <div>
                                                                    <textarea name="deskripsi_kelebihan_1" rows="10"
                                                                      class="form-control form-control-danger kelebihan"
                                                                      placeholder="Masukan Deskripsi Kelebihan"
                                                                      required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-10 col-xs-11">
                                                            <div class="title-top mar-b-1rem">{!! $kekurangan !!}</div>
                                                            <div class="form-group">
                                                                <div>
                                                                    <textarea name="deskripsi_kekurangan_1" rows="10"
                                                                      class="form-control form-control-danger kekurangan"
                                                                      placeholder="Masukan Deskripsi Kekurangan"
                                                                      required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="append-kk"></div>
                        </div>

                        <div class="col-md-12">
                            <a class="btn btn-primary" id="tambah-kk">
                                <em class="fa fa-plus"></em> Tambah {!! $kelebihan !!} dan {!! $kekurangan !!}
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="lh-70 float-right">
                                <a href="{{ route('master-data.kelebihan-kekurangan.index') }}" class="btn btn-warning">
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
            let indexKelebihan = $('input[name=index_kelebihan]')
            let indexKekurangan = $('input[name=index_kekurangan]')
            $(".select2").select2();
            indexKelebihan.val(1)
            indexKekurangan.val(1)

            window.tinymce.dom.Event.domLoaded = true;
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
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });
            var kk = 1;
            $("#tambah-kk").click(function () {
                kk = kk + 1;
                indexKelebihan.val(kk)
                indexKekurangan.val(kk)
                $(".append-kk").append("<div class=\"card card-kk" + kk + "\">\n" +
                    "                                <div class=\"card-body\">\n" +
                    "                                    <div class=\"card-header bg-blue\">\n" +
                    "                                        <div class=\"form-group title-top color-white\">\n" +
                    "                                            <label>Kategori</label>\n" +
                    "                                            <div>\n" +
                    "                                                <select class=\"form-control select2 select2-hidden-accessible\" required name=\"category_" + kk + "\">\n" +
                    "                                                    <option value=\"AMANAH\">Amanah</option>\n" +
                    "                                                    <option value=\"KOMPETEN\">Kompeten</option>\n" +
                    "                                                    <option value=\"HARMONIS\">Harmonis</option>\n" +
                    "                                                    <option value=\"LOYAL\">Loyal</option>\n" +
                    "                                                    <option value=\"ADAPTIF\">Adaptif</option>\n" +
                    "                                                    <option value=\"KOLABORATIF\">Kolaboratif</option>\n" +
                    "                                               </select>\n" +
                    "                                            </div>\n" +
                    "                                        </div>\n" +
                    "                                    </div>\n" +
                    "                                    <div class=\"row\">\n" +
                    "                                        <div class=\"col-md-6 col-xs-12\">\n" +
                    "                                            <div class=\"card\">\n" +
                    "                                                <div class=\"card-body\">\n" +
                    "                                                    <div class=\"row\">\n" +
                    "                                                        <div class=\"col-md-1 col-xs-1\">\n" +
                    "                                                            <span class=\"badge badge-primary numbering float-right\">" + kk + "</span>\n" +
                    "                                                        </div>\n" +
                    "                                                        <div class=\"col-md-10 col-xs-11\">\n" +
                    "                                                            <div class=\"title-top mar-b-1rem\">{!! $kelebihan !!}</div>\n"+
                    "                                                            <div class=\"form-group\">\n" +
                    "                                                                <div>\n" +
                    "\t\t\t\t\t\t\t\t\t\t\t\t\t<textarea name=\"deskripsi_kelebihan_\" rows=\"10\"\n" +
                    "                                                              class=\"form-control form-control-danger kelebihan\"\n" +
                    "                                                              placeholder=\"Masukan Deskripsi {!! $kelebihan !!}\"\n" +
                    "                                                              required></textarea>\n" +
                    "                                                                </div>\n" +
                    "                                                            </div>\n" +
                    "                                                        </div>\n" +
                    "                                                    </div>\n" +
                    "                                                </div>\n" +
                    "                                            </div>\n" +
                    "                                        </div>\n" +
                    "                                        <div class=\"col-md-6 col-xs-12\">\n" +
                    "                                            <div class=\"card\">\n" +
                    "                                                <div class=\"card-body\">\n" +
                    "                                                    <div class=\"row\">\n" +
                    "                                                        <div class=\"col-md-10 col-xs-11\">\n" +
                    "                                                            <div class=\"form-group\">\n" +
                    "                                                            <div class=\"title-top mar-b-1rem\">{!! $kekurangan !!}</div>\n"+
                    "                                                                <div>\n" +
                    "\t\t\t\t\t\t\t\t\t\t\t\t\t<textarea name=\"deskripsi_kekurangan_\" rows=\"10\"\n" +
                    "                                                              class=\"form-control form-control-danger kekurangan\"\n" +
                    "                                                              placeholder=\"Masukan Deskripsi {!! $kekurangan !!}\"\n" +
                    "                                                              required></textarea>\n" +
                    "                                                                </div>\n" +
                    "                                                            </div>\n" +
                    "                                                        </div>\n" +
                    "                                            <div class=\"col-md-1 col-xs-1\">\n" +
                    "                                                <i class=\"fa fa-trash-o delete-kk fa-2x color-red\" data-id=" + kk + "></i>\n" +
                    "                                            </div>\n" +
                    "                                                    </div>\n" +
                    "                                                </div>\n" +
                    "                                            </div>\n" +
                    "                                        </div>\n" +
                    "                                    </div>\n" +
                    "                                </div>\n" +
                    "                            </div>");

                var renum = 1;
                $(".wrapper-card .card .card-body span.numbering").each(function () {
                    $(this).text(renum);
                    renum++;
                });

                var kelebihan = 1;
                $(".wrapper-card .card .card-body textarea.kelebihan").each(function () {
                    $(this).attr('name','deskripsi_kelebihan_'+kelebihan);
                    kelebihan++;
                });

                var kekurangan = 1;
                $(".wrapper-card .card .card-body textarea.kekurangan").each(function () {
                    $(this).attr('name','deskripsi_kekurangan_'+kekurangan);
                    kekurangan++;
                });
                $(".select2").select2();
            });
        });

        $(document).on('click', '.delete-kk', function (e) {
            var value = $(this).data("id")
            $('.card-kk' + value + '').remove();
            var kk = $('input[name=index_kelebihan]').val();
            let indexKelebihan = $('input[name=index_kelebihan]');
            let indexKekurangan = $('input[name=index_kekurangan]');
            var kk = kk - 1;
            indexKelebihan.val(kk)
            indexKekurangan.val(kk)
            var renum = 1;
            $(".wrapper-card .card .card-body span.numbering").each(function () {
                $(this).text(renum);
                renum++;
            });
            var kelebihan = 1;
            $(".wrapper-card .card .card-body textarea.kelebihan").each(function () {
                $(this).attr('name','deskripsi_kelebihan_'+kelebihan);
                kelebihan++;
            });
            var kekurangan = 1;
            $(".wrapper-card .card .card-body textarea.kekurangan").each(function () {
                $(this).attr('name','deskripsi_kekurangan_'+kekurangan);
                kekurangan++;
            });
        });


    </script>
@endsection

