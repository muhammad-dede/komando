@foreach($list as $wa)

        @if(in_array($wa->id, $arr_perilaku))
            <div>
            {{--<input id="checkbox{{$wa->id}}" type="checkbox" name="perilaku[]" value="{{$wa->id}}" checked disabled>--}}

            <span for="checkbox{{$wa->id}}" class="text-muted">
                <i class="fa fa-check"></i> {!! $wa->perilaku !!}
            </span>
            </div>
        @else
            <div class="checkbox checkbox-primary">
            <input id="checkbox{{$wa->id}}" type="checkbox" name="perilaku[]" value="{{$wa->id}}">
            <label for="checkbox{{$wa->id}}">
                {!! $wa->perilaku !!}
            </label>
            </div>
        @endif


@endforeach