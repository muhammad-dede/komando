select V_LIQUID_MD_UP.PERNR as pernr_atasan,
       PA0001.PERNR            pernr_bawahan,
       PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'MD_UP') V_LIQUID_MD_UP
         join M_STRUKTUR_POSISI
              on (M_STRUKTUR_POSISI.SOBID = V_LIQUID_MD_UP.orgeh and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513
              on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '04' and HRP1513.SGRP = '04')
         join PA0001
              on (
                      PA0001."PLANS" = HRP1513.OBJID
                      and PA0001.BUKRS = V_LIQUID_MD_UP.BUKRS
                      and PA0001.BTRTL = V_LIQUID_MD_UP.BTRTL
                  )
UNION

select V_LIQUID_MD_UP.PERNR as pernr_atasan,
       PA0001.PERNR            pernr_bawahan,
       PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'MD_UP') V_LIQUID_MD_UP
         join M_STRUKTUR_ORGANISASI
              on (M_STRUKTUR_ORGANISASI.SOBID = V_LIQUID_MD_UP.orgeh)
         join M_STRUKTUR_POSISI
              on (M_STRUKTUR_POSISI.SOBID = M_STRUKTUR_ORGANISASI.objid and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513
              on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '04' and HRP1513.SGRP = '04')
         join PA0001
              on (
                      PA0001."PLANS" = HRP1513.OBJID
                      and PA0001.BUKRS = V_LIQUID_MD_UP.BUKRS
                      and PA0001.BTRTL = V_LIQUID_MD_UP.BTRTL
                  )


