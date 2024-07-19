@extends('layout')

@section('content')
    <div class="row">
        <div class="lh-70 col-md-3 col-lg-4 col-xs-12">
            <h4 class="page-title">Detail Survey LIQUID</h4>
        </div>
    </div>

    <div class="card-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Daftar Unit</label>

                        <div>
                            <ul style="padding-left: 15px">
                                @foreach ($liquid->businessAreas as $area)
                                    <li>{{ $area->description }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    @include('report.survey-liquid._progres_liquid')
                </div>
            </div>
        </div>
    </div>

    @foreach ($questions as $question)
        <div class="row">
            <div class="col-md-12">
                <h5>Pertanyaan: {{ $question->title }}</h5>
            </div>
        </div>

        <div class="card-box">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="thead-blue">
                        <tr>
                            <th>Nama Atasan</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Unit Kerja</th>
                            <th>Score</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($question->result as $result)
                            @php
                                $item = $result->first();
                            @endphp
                            <tr>
                                <td>{!! $item->atasan->nama !!}</td>
                                <td>{!! $item->atasan->nip !!}</td>
                                <td>{!! $item->atasan->jabatan !!}</td>
                                <td>{!! $item->atasan->unit !!}</td>
                                <td>{!! number_format($result->avg('score'), 2, ',', '.') !!}</td>
                                <td>
                                @foreach($result as $survey)
                                    @if(!empty($survey->reason))
                                        {!! "- ".$survey->reason."<br />" !!}
                                    @else
                                        Alasan tidak ditemukan.
                                    @endif
                                @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('javascript')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });
    </script>
@stop
