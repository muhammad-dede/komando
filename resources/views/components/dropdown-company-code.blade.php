<div class="{{ $grid }}">
    <select class="form-control form-control-danger select2" aria-hidden="true" name="company_code" id="company_code">
        <option selected="selected" disabled>Company Code</option>
        <option value="1">All Unit</option>
        <option value="2">Kantor Pusat</option>
        <option value="3">UID Jawa Barat</option>
        <option value="4">UID Jawa Tengah</option>
    </select>
</div>
<div class="{{ $grid }}">
    <select class="form-control form-control-danger  select2" aria-hidden="true" name="business_area" id="business_area">
        <option selected="selected" disabled>Business Area</option>
        <option value="3">Kantor Kab. Bandung</option>
        <option value="3">Kantor Kab. Indramayu</option>
        <option value="4">Kantor Kab. Brebes</option>
        <option value="4">Kantor Kab. Semarang</option>
    </select>
</div>
@push('scripts')
    <script>
        $( document ).ready(function() {
            var $select1 = $( '#company_code' ),
                $select2 = $( '#business_area' ),
                $options = $select2.find( 'option' );

            $select1.on( 'change', function() {
                $select2.html( $options.filter( '[value="' + this.value + '"]' ) );
            } ).trigger( 'change' );
        });
    </script>
@endpush
