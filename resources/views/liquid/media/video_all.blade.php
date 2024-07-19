@extends('layout')

@section('content')
    <br>
	@php($videos = \App\Models\MediaKit::getVideoGallery(\App\Enum\MediaKitStatus::ACTIVE, true))
    <div class="row">
        @forelse($videos as $video)
            <div class="col-md-4 col-xs-12 col-sm-12">
                <h3 class="m-b-10 text-info mar-b-1rem"><i class="fa fa-youtube-play" aria-hidden="true"></i> {{ $video['judul'] }}</h3>
                <video width="100%" controls="">
                    <source src="{{ $video['url'] }}">
                    Your browser does not support HTML5 video.
                </video>
            </div>
        @empty
            Tidak ada video yang ditampilkan
        @endforelse
    </div>
@endsection
