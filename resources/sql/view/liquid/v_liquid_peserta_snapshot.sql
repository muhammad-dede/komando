select LIQUID_PESERTA.ID,
       LIQUID_PESERTA.LIQUID_ID,
       ATASAN_ID,
       jabatan_atasan.CNAME                    nama_atasan,
       jabatan_atasan.NIP                      nip_atasan,
       posisi_atasan.STEXT                     jabatan_atasan,
       PA0001_atasan.GSBER                     business_area_atasan,
       LIQUID_PESERTA.SNAPSHOT_JABATAN_ATASAN  kelompok_jabatan_atasan,

       BAWAHAN_ID,
       jabatan_bawahan.CNAME                   nama_bawahan,
       jabatan_bawahan.NIP                     nip_bawahan,
       posisi_bawahan.STEXT                    jabatan_bawahan,
       PA0001_bawahan.GSBER                    business_area_bawahan,
       LIQUID_PESERTA.SNAPSHOT_JABATAN_BAWAHAN kelompok_jabatan_bawahan

from LIQUID_PESERTA

         left join M_STRUKTUR_JABATAN jabatan_atasan on (jabatan_atasan.PERNR = ATASAN_ID)
         left join M_STRUKTUR_POSISI posisi_atasan
              on (posisi_atasan.OBJID = jabatan_atasan."PLANS" and posisi_atasan.RELAT = 3)
         left join PA0001 PA0001_atasan on (PA0001_atasan.PERNR = ATASAN_ID)

         left join M_STRUKTUR_JABATAN jabatan_bawahan on (jabatan_bawahan.PERNR = BAWAHAN_ID)
         left join M_STRUKTUR_POSISI posisi_bawahan
              on (posisi_bawahan.OBJID = jabatan_bawahan."PLANS" and posisi_bawahan.RELAT = 3)
         left join PA0001 PA0001_bawahan on (PA0001_bawahan.PERNR = BAWAHAN_ID)
where LIQUID_PESERTA.DELETED_AT IS NULL
