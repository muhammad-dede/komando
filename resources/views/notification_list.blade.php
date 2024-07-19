@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Notification</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                {{-- Button Mark As Read All Notification --}}
                <div class="row m-b-20">
                    <div class="col-md-12">
                        <a href="{{url('notification/clear-all')}}" class="btn btn-primary"><i class="fa fa-trash"></i> Clear All Notification</a>
                    </div>
                </div>
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($notifications as $notif)
                        @if($notif->status=='UNREAD')
                            <tr class="even gradeC" onclick="window.location.href='{{url('notification/'.$notif->id)}}'" onmouseover="this.style.cursor='pointer'">
                                <td><b>{{str_pad($notif->id,8,'0', STR_PAD_LEFT)}}</b></td>
                                <td><b>{{$notif->froms->name}}</b></td>
                                <td><b>{{$notif->subject}}</b></td>
                                <td><b>{{$notif->message}}</b></td>
                                <td><b>{{$notif->created_at->diffForHumans()}}</b></td>
                            </tr>
                        @else
                            <tr class="even gradeC" onclick="window.location.href='{{url($notif->url)}}'" onmouseover="this.style.cursor='pointer'">
                                <td>{{str_pad($notif->id,8,'0', STR_PAD_LEFT)}}</td>
                                <td>{{$notif->froms->name}}</td>
                                <td>{{$notif->subject}}</td>
                                <td>{{$notif->message}}</td>
                                <td>{{$notif->created_at->diffForHumans()}}</td>
                            </tr>
                        @endif

                    @endforeach

                    </tbody>
                </table>
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
            $("#company_code").select2();
//            $("#tahun").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable({
                "order": [[ 0, "desc" ]]
            });
        });

    </script>
@stop