@push('styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/light-gallery/dist/css/lightgallery.min.css') }}" />
@endpush
<div class="card-box width-full">
    <div class="table-responsive">
        <table class="datatable table table-striped table-bordered">
            <thead class="thead-blue">
            <tr>
                <th class="color-white vertical-middle">No</th>
                <th class="color-white vertical-middle">Thumbnail</th>
                <th class="color-white vertical-middle">URL</th>
                <th class="color-white vertical-middle">Judul</th>
                <th class="color-white vertical-middle">Jenis</th>
                <th class="color-white vertical-middle">Status</th>
                <th class="align-center color-white vertical-middle" style="min-width: 100px;">Aksi
                </th>
            </tr>
            </thead>
            <tbody>
            @php($i = 1)
            @foreach($items as $index => $item)
                @php
                    $firstMedia = $item->getFirstMedia();
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td width="50px">
                        @if (file_exists($firstMedia->getPath()))
                            {!! app_media_thumbnail($item->getFirstMedia()) !!}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="url">{{ $item->getMedia()[0]->getUrl() }}</div>
                        <button class="btn btn-blue-negative-sm" data-clipboard-text="{{ $item->getMedia()[0]->getUrl() }}">
                            <i aria-hidden="true" class="fa fa-copy"></i> Copy to Clipboard</button></td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>
                        {{ $item->status }}
                    </td>
                    <td>
                        <div class="display-flex">
                            <a href="{{ route('manajemen-media-banner.show', $item->getKey()) }}" class="btn btn-primary margin-rl-5">
                                <em class="fa fa-eye" data-toggle="tooltip"
                                    title="detail">
                                </em>
                            </a>
                            <a href="{{ route('manajemen-media-banner.edit', $item->getKey()) }}" class="btn btn-warning margin-rl-5"
                               data-toggle="tooltip" title="edit">
                                <em class="fa fa-pencil">
                                </em>
                            </a>
                            <form action="{{ route('manajemen-media-banner.destroy', $item->getKey()) }}" method="post" class="margin-rl-5">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-danger hapus" type="button" data-toggle="tooltip" title="hapus" onclick="deleteMedia()">
                                    <em class="fa fa-trash-o"></em>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('vendor/light-gallery/dist/js/lightgallery.min.js') }}"></script>
    <script src="{{ asset('vendor/clipboard/clipboard.min.js') }}"></script>
    <script>
        function deleteMedia() {
            event.preventDefault(); // prevent form submit
            var form = event.target.form;
            console.log(form)
            swal({
                title: "Apa Anda yakin?",
                text: "Media & Banner akan dihapus secara permanen!",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    form.submit();
                    swal.close();
                } else {
                    swal.close();
                }
            });
        }
        $(document).ready(function () {
            $('.image').lightGallery({
                selector: 'this',
            });

            let clipboard = new ClipboardJS('.btn');
            clipboard.on('success', function(e) {
                $(e.trigger).text("Copied!");
                e.clearSelection();
                setTimeout(function() {
                    $(e.trigger).text("Copy to Clipboard");
                }, 2500);
            });
        });
    </script>
@endpush
