@inject('jabatan', 'App\Services\JenjangJabatanService')
<select class="select2 form-control form-control-danger"
        name="jabatan"
        tabindex="-1"
        aria-hidden="true">
    <option value="" {{ request('jabatan') === '' ? 'selected' : '' }}>Semua Jabatan</option>
    @foreach(\App\Enum\LiquidJabatan::toDropdownArray() as $data => $label)
        @if($data != 'STAF')
            <option value="{{ $data }}"  {{ request('jabatan') === $data ? 'selected' : '' }}>{{ $label }}</option>
        @endif
    @endforeach
</select>
