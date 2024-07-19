<div id="{{ $id }}" data-ride="carousel" class="carousel slide">
    <ol class="carousel-indicators">
        @foreach($images as $index => $image)
            <li data-target="#{{ $id }}" data-slide-to="{{ $index }}"
                class="{{ $index === 0 ? 'active': '' }}"></li>
        @endforeach
    </ol>
    <div role="listbox" class="carousel-inner">
        @foreach($images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active': '' }}">
                @if($image['link']!='')
                <a href="{{ $image['link'] }}" target="blank"><img src="{{ $image['url'] }}" alt=""></a>
                @else
                <img src="{{ $image['url'] }}" alt="">
                @endif
            </div>
        @endforeach
    </div>
    <a href="#{{ $id }}" role="button" data-slide="prev" class="left carousel-control">
        <span aria-hidden="true" class="fa fa-angle-left"></span> <span class="sr-only">Previous</span> </a>
    <a href="#{{ $id }}" role="button" data-slide="next" class="right carousel-control">
        <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Next</span> </a>
</div>
