SELECT DISTINCT liquids.id                                     liquid_id,
                EXTRACT(year FROM liquids.FEEDBACK_START_DATE) tahun,
                M_COMPANY_CODE.COMPANY_CODE,
                M_BUSINESS_AREA.BUSINESS_AREA,
                atasan_id                                      pernr,
                SNAPSHOT_NAMA_ATASAN                           nama,
                SNAPSHOT_NIP_ATASAN                            nip,
                SNAPSHOT_JABATAN2_ATASAN                       jabatan,
                SNAPSHOT_JABATAN_ATASAN                        kelompok_jabatan,
                SNAPSHOT_UNIT_CODE                             unit_code,
                SNAPSHOT_UNIT_NAME                             unit_name
FROM LIQUID_PESERTA
      left   JOIN LIQUIDS ON (LIQUIDS.id = LIQUID_ID)
      left    JOIN M_BUSINESS_AREA ON (M_BUSINESS_AREA.BUSINESS_AREA = LIQUID_PESERTA.SNAPSHOT_UNIT_CODE)
       left join M_COMPANY_CODE on (M_COMPANY_CODE.COMPANY_CODE = M_BUSINESS_AREA.COMPANY_CODE)
WHERE liquids.STATUS = 'PUBLISHED'
