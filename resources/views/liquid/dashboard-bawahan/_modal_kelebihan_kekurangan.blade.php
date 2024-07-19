<div class="modal fade" id="{{ 'feedback_'.($index+1) }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="title title-top" id="exampleModalLabel">{!! $kelebihan !!} | {!! $kekurangan !!}</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                        @php($i=1)
                        <tr>
                            <th colspan="2">{!! $kelebihan !!}</th>
                        </tr>
                        @foreach ($penilaian['feedback_kelebihan'] as $feedback)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $feedback }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">{!! $kekurangan !!}</th>
                        </tr>
                        @php($i=1)
                        @foreach ($penilaian['feedback_kekurangan'] as $feedback)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $feedback }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
