<h5><b>{{ empty($usulan_atasan) ? 'Daftar Usulan Atasan' : $usulan_atasan }}</b></h5>
<table id="table_self_service" class="table table-striped table-bordered" style="width: 100%;">
    <thead class="thead-blue">
        <tr>
            <th style="text-align: center">Bawahan</th>
            <th style="text-align: center">Atasan</th>
        </tr>
    </thead>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#table_self_service').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                columns: [
                    // { "data": "no", "class" : "text-center", "width":"5%", 'sortable' : false },
                    {
                        "data": "snapshot_nama_bawahan",
                        "sortable" : true,
                        "render": function(data, type, full, meta) {
                            var tmp;
                            tmp = `${full.snapshot_nip_bawahan} <br> <b>${data}</b> <br> ${full.snapshot_jabatan2_bawahan}`
                            return tmp;
                        }
                    },
                    {
                        "data": "snapshot_nama_atasan",
                        "sortable" : true,
                        "render": function(data, type, full, meta) {
                            var tmp;
                            tmp = `${full.snapshot_nip_atasan} <br> <b>${data}</b> <br> ${full.snapshot_jabatan2_atasan}`
                            return tmp;
                        }
                    },
                ],
                // "order" : [[1, 'desc']],
                "ajax": {
                    "url" : "{{ route('dashboard-admin.self-service.selfService') }}",
                    "type" : "POST",
                    "data" : (d)=> {
                        let urlParams = new URLSearchParams(window.location.search),
                            unitCode = urlParams.get('unit_code'),
                            divisi = urlParams.get('divisi'),
                            year = urlParams.get('year');

                        unitCode = $('select[name=unit_code]').val();
                        divisi = $('select[name=divisi]').val();
                        year = $('select[name=year]').val();
                        
                        d._token = '{{ csrf_token() }}';
                        d.unit_code = unitCode;
                        d.divisi = divisi;
                        d.year = year;
                    }
                },
            });
        });
    </script>
@endpush