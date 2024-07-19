@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Pedoman Perilaku</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">

                {{--<h4 class="m-t-0 header-title"><b>Default Example</b></h4>--}}

                {{--<p class="text-muted font-13 m-b-30">--}}
                    {{--DataTables has most features enabled by default, so all you need to do to use it with--}}
                    {{--your own tables is to call the construction function: <code>$().DataTable();</code>.--}}
                {{--</p>--}}

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th width="50" style="text-align: center">Nomor</th>
                        <th style="text-align: center">Pedoman Perilaku</th>
                        <th style="text-align: center">Jml. Pertanyaan</th>
                    </tr>
                    </thead>


                    <tbody>
                    @foreach($pedoman_list as $pedoman)
                    <tr>
                        <td>{{$pedoman->nomor_urut}}</td>
                        <td><a href="{{url('master-data/pedoman-perilaku/'.$pedoman->id.'/display')}}">{{$pedoman->pedoman_perilaku}}</a></td>
                        <td align="center">{{$pedoman->pertanyaan()->where('status','ACTV')->count()}}</td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
                <!-- end row -->

            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable();
        });

    </script>

@stop