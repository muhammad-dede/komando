                                    <div style="margin-left:15px;"><small class="text-muted">* Pilih salah satu Isu Nasional untuk dibaca waktu CoC.</small></div>
                                    <div class="col-md-12" style="overflow: auto;height: 350px;" id="tabel_isu">
                                        <table class="table m-t-10">
                                            <thead>
                                                <tr>
                                                    <th>

                                                    </th>
                                                    <th>
                                                        Isu Strategis
                                                    </th>
                                                    <th width="200">
                                                        Kategori
                                                    </th>
                                                    {{-- <th width="200">
                                                        Isu Nasional
                                                    </th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($isu_nasional_list as $data)
                                                <tr>
                                                    <td width="20" style="padding:10px;">
                                                        <label class="c-input c-radio">
                                                            <input id="radio_isu_{{$data->id}}" name="isu_nasional_id" type="radio" value="{{$data->id}}" {{ ($data->id==$isu_nasional_select->id)?'checked':'' }}>
                                                            <span class="c-indicator"></span>
                                                        </label>
                                                    </td>
                                                    <td style="padding:10px;">
                                                        {!! $data->description !!}
                                                    </td>
                                                    <td style="padding:10px;"> 
                                                        {!! $data->sub_header !!}
                                                    </td>
                                                    {{-- <td style="padding:10px;">
                                                        {!! $data->isu_nasional !!}
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                               