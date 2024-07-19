@php($videos = \App\Models\MediaKit::getVideoGallery(\App\Enum\MediaKitStatus::ACTIVE))

<div class="row m-t-30 m-b-30">
    @forelse($videos as $video)
        <div class="col-md-6 col-xs-12 col-sm-12">
            <h3 class="m-b-10 text-info"><i class="fa fa-youtube-play"></i> {{ $video['judul'] }}</h3>

            <video width="100%" controls="">
                <source src="{{ $video['url'] }}">
                Your browser does not support HTML5 video.
            </video>
        </div>

    @empty
        Tidak ada video yang ditampilkan
	@endforelse
	<div class="col-md-6 pull-right">
		<a href="{{ route('videos.all') }}">Show All</a>
	</div>
</div>
