
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/dist/fullcalendar.min.css')}}"/>
@endpush

<div class="card-box">
    <div id="calendar"></div>
</div>

@push('scripts')
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('assets/plugins/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
            $('.datatable').DataTable();
            $('#calendar').fullCalendar({
                "header": {
                    "left": "prev,next today",
                    "center": "title",
                    "right": "month,agendaWeek,agendaDay"
                },
                "eventLimit": false,
                "firstDay": 1,
                "events": {!! json_encode($jadwalLiquid) !!}
            });
        });
    </script>
@endpush

