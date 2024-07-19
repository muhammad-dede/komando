<?php

use App\Enum\LiquidJabatan;

return [
    'disable_date_validation' => env('LIQUID_DISABLE_DATE_VALIDATION', false),
    'mapping_jabatan_penilai' => [
        LiquidJabatan::EVP => [
            LiquidJabatan::VP => 'v_penilai_evp_dari_vp',
            LiquidJabatan::MD_PUSAT => 'v_penilai_evp_dari_manajer',
            LiquidJabatan::STAF => 'v_penilai_evp_dari_staf',
        ],
        LiquidJabatan::GM => [
            LiquidJabatan::SRM => 'v_penilai_gm_dari_srm',
            LiquidJabatan::MD_UP => 'v_penilai_gm_dari_mup',
            LiquidJabatan::STAF => 'v_penilai_gm_dari_staf',
        ],
        LiquidJabatan::VP => [
            LiquidJabatan::MANAGER => 'v_penilai_vp_dari_msb',
            LiquidJabatan::STAF => 'v_penilai_vp_dari_staf',
        ],
        LiquidJabatan::SRM => [
            LiquidJabatan::MANAGER => 'v_penilai_srm_dari_msb',
            LiquidJabatan::STAF => 'v_penilai_srm_dari_staf',
        ],
        LiquidJabatan::MD_UP => [
            LiquidJabatan::SPV => 'v_penilai_md_up_dari_spv',
            LiquidJabatan::STAF => 'v_penilai_md_up_dari_staf',
            LiquidJabatan::SPV_ATAS_SUP => 'v_penilai_md_up_dari_spv_atas',
        ],
        // TODO: jabatan ini tidak diikutkan LIQUID di tahun 2020
        // TODO: ini yang bikin query lama, perlu optimasi juga
        LiquidJabatan::MD_PUSAT => [
            // LiquidJabatan::SPV => 'v_penilai_md_pusat_dari_spv',
            // LiquidJabatan::STAF => 'v_penilai_md_pusat_dari_staf',
        ],
        LiquidJabatan::SPV_ATAS_SUP => [
            LiquidJabatan::SPV => 'v_penilai_spv_sup_dari_spvd',
            LiquidJabatan::STAF => 'v_penilai_spv_sup_dari_staf',
        ],
    ],
    'word_count' => (int) env('WORD_COUNT', 5),
];
