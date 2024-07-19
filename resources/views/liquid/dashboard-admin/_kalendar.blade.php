@push('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/dist/fullcalendar.min.css')}}"/>
@endpush

<div class="card-box">
    <div id="calendar"></div>
</div>

@push('scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function(){
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
