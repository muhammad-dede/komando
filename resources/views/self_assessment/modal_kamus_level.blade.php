<div id="modal_kamus_level" class="modal fade" role="dialog" aria-labelledby="kamusLevelModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Level Profisiensi Kompetensi </h4>
                </div>
                <div class="modal-body">

                    <table class="table table-hover">
                        @foreach($kamus_level as $level)
                        <tr>
                            <td width="150" align="center" style="text-align: center;vertical-align: top;">
                                {{-- <span style="font-weight: bold; font-size:16px;">Level</span> --}}
                                <div>
                                    <span class="label label-success" style="font-weight: bold; font-size:25px;" title="Level {{ $level->level }}"><small>Lv.</small>{{ $level->level }}</span>
                                </div>
                            </td>
                            <td>
                                <b>{{ $level->tingkat_kecakapan }}</b>
                                <div id="level_profisiensi_{{ $level->level }}" class="rating-sm" style="margin-top: 10px;"></div>
                                @push('skrip')
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('#level_profisiensi_{{ $level->level }}').raty({
                                            readOnly: true,
                                            number: {{ $jml_level }},
                                            score: {{ $level->level }},
                                            starOff: 'fa fa-star-o text-muted',
                                            starOn: 'fa fa-star text-danger',
                                            hints: [{!! $hint !!}]
                                        });
                                    });
                                    </script>
                                @endpush
                            </td>
                            <td>
                                <p>{!! $level->pedoman_kriteria_kinerja !!}</p>
                                <p>{!! $level->taksonomi_umum !!}</p>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->