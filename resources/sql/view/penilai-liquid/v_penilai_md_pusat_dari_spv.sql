select V_LIQUID_MD_PUSAT.PERNR as pernr_atasan,
       PA0001.PERNR               pernr_bawahan,
       PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'MD_PUSAT') V_LIQUID_MD_PUSAT
         join M_STRUKTUR_POSISI
              on (M_STRUKTUR_POSISI.SOBID = V_LIQUID_MD_PUSAT.orgeh and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513
              on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '04' and HRP1513.SGRP = '04')
         join PA0001
              on (
                      PA0001."PLANS" = HRP1513.OBJID
                      and PA0001.BUKRS = V_LIQUID_MD_PUSAT.BUKRS
                      and PA0001.BTRTL = V_LIQUID_MD_PUSAT.BTRTL
                  )
UNION

select V_LIQUID_MD_PUSAT.PERNR as pernr_atasan,
       PA0001.PERNR               pernr_bawahan,
       PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'MD_PUSAT') V_LIQUID_MD_PUSAT
         join M_STRUKTUR_ORGANISASI
              on (M_STRUKTUR_ORGANISASI.SOBID = V_LIQUID_MD_PUSAT.orgeh)
         join M_STRUKTUR_POSISI
              on (M_STRUKTUR_POSISI.SOBID = M_STRUKTUR_ORGANISASI.objid and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513
              on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '04' and HRP1513.SGRP = '04')
         join PA0001
              on (
                      PA0001."PLANS" = HRP1513.OBJID
                      and PA0001.BUKRS = V_LIQUID_MD_PUSAT.BUKRS
                      and PA0001.BTRTL = V_LIQUID_MD_PUSAT.BTRTL
                  )


