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
        <div class="col-md-6 col-xs-6 lh-70">
            <h4 class="page-title">Tabel Info Penyelarasan</h4>
        </div>
        <div class="col-md-6 col xs-6 align-right lh-70">
            @can('inputPenyelarasan', $liquid)
                <a
                    id="add-resolusi"
                    {{-- href="{{ route('penyelarasan.create', ['liquid_id' => $liquid->getKey()]) }}" --}}
                    href="{{
                        $isValid
                            ? route('penyelarasan.create', ['liquid_id' => $liquid->getKey()])
                            : '#'
                    }}"
                    class="btn btn-primary"
                >
                    <em class="fa fa-plus"></em>
                    Add Resolusi
                </a>
            @else
                <button class="btn" disabled>
                    <em class="fa fa-plus"></em>
                    Add Resolusi
                </button>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered kelebihan comp-list" aria-describedby="History Resolusi">
                        <thead class="thead-blue">
                        <tr>
                            <th scope="col" class="lh-35 color-white" style="min-width: 170px;">Tanggal Pelaksanaan</th>
                            <th scope="col" class="lh-35 color-white" style="min-width: 150px;">Tempat Pelaksanaan</th>
                            <th scope="col" class="lh-35 color-white">Resolusi</th>
                            <th scope="col" class="lh-35 color-white">Deskripsi Kegiatan</th>
                            <th scope="col" class="lh-35 color-white">Aksi Nyata</th>
                            <th scope="col" class="lh-35 color-white">Keterangan</th>
                            <th scope="col" class="align-center lh-35 title-top color-white" style="min-width: 100px;">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($penyelarasans as $index => $item)
                                <tr>
                                    <td>
										<strong>{{ $item->date_start->format('d-m-Y') }}</strong>
                                    </td>
                                    <td>{{ $item->tempat }}</td>
                                    <td>
                                        <ol>
                                            @foreach ($resolusis[$index] as $res)
                                                <li>{{ $res }}</li>
                                            @endforeach
                                        </ol>
                                    </td>
                                    <td>
                                        {!! $item->keterangan !!}
                                    </td>
                                    <td>
                                        <ol>
                                        @foreach($item->aksi_nyata as $aksi)
                                            <li>{{ $aksi }}</li>
                                        @endforeach
                                        </ol>
                                    </td>
                                    <td>
                                        @if (empty($item->keterangan_aksi_nyata))
                                            -
                                        @else
                                            <ol>
                                                @foreach ($item->keterangan_aksi_nyata as $ket)
                                                    <li>{{ $ket }}</li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="" data-toggle="modal"
                                           data-target="{{ "#detail_$index" }}" class="badge badge-primary"><em class="fa fa-eye fa-2x"></em></a>
                                        @can('penyelarasanInProgress', $liquid)
                                            <a href="{{ route('penyelarasan.edit', $item->id) }}" class="badge badge-warning"><em class="fa fa-pencil fa-2x"></em></a>
                                            <form action="{{ route('penyelarasan.destroy', $item->id) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="badge badge-danger"
                                                    onclick="return confirm('Apakah Anda Yakin?')"><em class="fa fa-trash-o fa-2x"></em></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($penyelarasans as $index => $item)
        <div class="modal fade" id="{{ "detail_$index" }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="title title-top" id="exampleModalLabel">Detail Resolusi
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body border-unset">
                        <table class="table tabel-border" border="1">
                            <tr>
                                <td>Tanggal Pelaksanaan</td>
                                <td>
                                    <strong>{{ $item->date_start->format('d-m-Y') }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Tempat Pelaksanaan</td>
                                <td>{{ $item->tempat }}</td>
                            </tr>
                            <tr>
                                <td>Resolusi</td>
                                <td>
                                    <ol class="pad-l-1rem">
                                        @foreach ($resolusis[$index] as $key => $res)
                                            <li style="{{ $key !== 0 ? 'margin-top:20px;' : '' }}">{{ $res }}</li>
                                            <ul>
                                                <li>
                                                    <strong>Aksi Nyata: </strong> {{ $aksiNyatas[$index][$key] }}
                                                </li>
                                                <li>
                                                    <strong>Keterangan: </strong> {{ empty($keterangans[$index][$key]) ? '-' : $keterangans[$index][$key] }}
                                                </li>
                                            </ul>
                                        @endforeach
                                    </ol>
                                </td>
                            </tr>
                            <tr>
                                <td>Deskripsi Kegiatan</td>
                                <td>
                                    {!! $item->keterangan !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer border-unset">
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable();

            $('#add-resolusi').click(function () {
                if ($(this).attr('href') === '#') {
                    swal({
                        title: "Warning!",
                        text: 'Bawahan anda kurang dari 3, tidak dapat lanjut ke proses selanjutnya, silahkan hubungi administrator',
                        type: "warning",
                        showCancelButton: false,
                        cancelButtonClass: 'btn-secondary waves-effect',
                        confirmButtonClass: 'btn-primary waves-effect waves-light',
                        confirmButtonText: 'OK',
                    });
                }
            });
        });
    </script>
@stop
