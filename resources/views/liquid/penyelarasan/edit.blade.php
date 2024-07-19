@extends('layout')

@section('css')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Edit Penyelarasan</h4>
        </div>
    </div>
@stop

@section('content')
    @include('components.flash')
    <form action="{{ route('penyelarasan.update', $penyelarasan->id) }}" method="post">
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Penyelarasan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link next" data-toggle="tab" href="#tabs-2" role="tab">Tanggal Penyelarasan</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content comp-tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered" aria-describedby="Catatan Kekurangan">
                                        <thead class="thead-blue">
                                        <tr>
                                            <th scope="col" class="lh-35 title-top color-white">Nomor</th>
                                            <th scope="col" class="lh-35 title-top color-white">Resolusi</th>
                                            <th scope="col" class="lh-35 title-top color-white">Aksi Nyata</th>
                                            <th scope="col" class="lh-35 title-top color-white">Keterangan</th>
                                            <th scope="col" class="align-center title-top color-white">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($feedbackData['kk_details'] as $index => $item)
                                            @php
                                                $no = $index + 1;
                                            @endphp
                                            <tr>
                                                <td>Resolusi {{ $no }}</td>
                                                <td align="left">{{ $item }}</td>
                                                <td id="{{ 'aksi_nyata_' . $no }}">
                                                    {{ old('aksi_nyata_' . $no, $feedbackData['aksi_nyata'][$index]) }}
                                                </td>
                                                <td id="{{ 'keterangan_' . $no }}">
                                                    {{ old('keterangan_' . $no, $feedbackData['keterangan_aksi_nyata'][$index]) }}
                                                </td>
                                                <td align="center">
                                                    <span
                                                        class="badge badge-warning" data-toggle="modal"
                                                        data-target="#resolusi"
                                                    >
                                                        <em
                                                            class="fa fa-pencil fa-2x edit-res"
                                                            data-index="{{ $no }}"
                                                        ></em>
                                                    </span>
                                                    <input
                                                        type="hidden"
                                                        name="{{ 'resolusi_' . $no }}"
                                                        value="{{ $feedbackData['resolusi_ids'][$index] }}"
                                                    >
                                                    <input
                                                        type="hidden"
                                                        name="{{ 'aksi_nyata_' . $no }}"
                                                        value="{{ old('aksi_nyata_' . $no, $feedbackData['aksi_nyata'][$index]) }}"
                                                    >
                                                    <input
                                                        type="hidden"
                                                        name="{{ 'keterangan_' . $no }}"
                                                        value="{{ old('keterangan_' . $no, $feedbackData['keterangan_aksi_nyata'][$index]) }}"
                                                    >
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-blue lh-35">
                                            <span class="title-top color-white">Data Most of Improvement Hasil Voter</span>
                                        </div>
                                        <div class="card-body overflow-auto-y" style="height: 400px;">
                                            <table class="table table-striped table-bordered" aria-describedby="Data Kekurangan Hasil Voter">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Most of Improvement</th>
                                                    <th scope="col" class="align-center">Voter</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($feedbackData['voter'] as $label => $voter)
                                                        <tr>
                                                            <td>{{ $label }}</td>
                                                            <td align="center">{{ $voter }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-blue lh-35">
                                            <span class="title-top color-white">Most Strength</span>
                                        </div>
                                        <div class="card-body overflow-auto-y" style="height: 400px;">
                                            @forelse ($feedbackData['kekurangan_notes'] as $note)
                                            <div class="row mar-b-1rem">
                                                <div class="col-md-2">
                                                    <img src="{{ asset('assets/images/user.jpg') }}" class="img-circle img-fluid" alt="">
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="title-blue">Anonymous</div>
                                                    <div class="desc">
                                                        {{ $note }}
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            [Tidak Ada]
                                            @endforelse
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 pull-right">
                                    <div class="button-list">
                                        <button data-toggle="tab" href="#tabs-2" role="tab" class="btn btn-primary next btn-lg pull-right"><em
                                                    class="fa fa-arrow-right"></em>
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tanggal Mulai Pelaksanaan <span class="text-danger">*</span></label>
                                            <div>
                                                <div class="input-group">
                                                    @php
                                                        $dateValue = $penyelarasan->date_start->format('d-m-Y');
                                                    @endphp
                                                    <input type="hidden" name="date_end" id="date-end" value="{{ $dateValue }}">
                                                    <input
                                                        type="text"
                                                        value="{{ $dateValue }}"
                                                        autocomplete="off"
                                                        class="form-control tanggal" name="date_start"
                                                    />
                                                    <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-calender"></em></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Tempat Kegiatan<span class="text-danger">*</span></label>
                                            <div>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="tempat_kegiatan"
                                                        value="{{ $penyelarasan->tempat }}"
                                                        autocomplete="off"/>
                                                    <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-location-pin"></em></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="pernr_leader" class="form-control-label">Deskripsi</label>
                                            <div>
                                            <textarea name="deskripsi" rows="20"
                                                class="form-control form-control-danger deskripsi mar-r-1rem"
                                                placeholder="Masukan Deskripsi Program">{!! $penyelarasan->keterangan !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 pull-right">
                                    <div class="button-list">
                                        <button type="submit" class="btn btn-primary btn-lg pull-right"><em
                                            class="fa fa-save"></em>
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="resolusi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="title title-top" id="exampleModalLabel">Input Aksi Nyata</span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">
                                Aksi Nyata <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="edit-res" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Keterangan :</label>
                            <textarea class="form-control" id="edit-keterangan" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <em class="fa fa-times"></em> Cancel
                        </button>

                        <button type="button" class="btn btn-primary" id="save-cust-resolusi"><em
                            class="fa fa-save"></em> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable();

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
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
            });

            let dataIndex;
            let editResVal = $('textarea#edit-res');
            let editKetVal = $('#edit-keterangan');

            $('em.edit-res').click(function () {
                dataIndex	= $(this).attr('data-index')

                let resVal	= $(`input[name=aksi_nyata_${dataIndex}]`).val()

                editResVal.val(resVal)
                editKetVal.val(
                    $('input[name="keterangan_' + dataIndex + '"]').val()
                )
            })

            $('#save-cust-resolusi').click(function () {
                let resVal = $(`input[name=aksi_nyata_${dataIndex}]`)
                let ketVal = $(`input[name=keterangan_${dataIndex}]`)

                if (editResVal.val() === '' || editResVal.val() === null) {
                    swal({
                        title: "Warning!",
                        text: "Kolom aksi nyata tidak boleh kosong",
                        type: "warning",
                        showCancelButton: false,
                        cancelButtonClass: 'btn-secondary waves-effect',
                        confirmButtonClass: 'btn-primary waves-effect waves-light',
                        confirmButtonText: 'OK',
                    });

                    $(`#aksi_nyata_${dataIndex}`).text(resVal.val())
                } else {
                    if (resVal !== editResVal.val()) {
                        $(`#aksi_nyata_${dataIndex}`).text(editResVal.val())
                        $(`input[name=aksi_nyata_${dataIndex}]`).val(editResVal.val())
                    }

                    if (ketVal !== editKetVal.val()) {
                        $(`#keterangan_${dataIndex}`).text(editKetVal.val())
                        $(`input[name=keterangan_${dataIndex}]`).val(editKetVal.val())
                    }

                    $('#resolusi').modal('hide');
                }
            })

            $('[name="date_start"]').change(function () {
                let value = $(this).val();

                $('#date-end').val(value);
            })
        });

        jQuery('.tanggal').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            orientation: "left"
        });

        $('.btn-primary.next').click(function () {
            $('.nav-link.next').addClass('active')
            $( ".nav-link" ).not( ".next" ).removeClass('active')
        })
    </script>
@stop
