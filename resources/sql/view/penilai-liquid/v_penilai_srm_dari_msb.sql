select V_LIQUID_SRM.PERNR as pernr_atasan, PA0001.PERNR pernr_bawahan, PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'SRM') V_LIQUID_SRM
         join M_STRUKTUR_POSISI on (M_STRUKTUR_POSISI.SOBID = V_LIQUID_SRM.orgeh and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513 on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '04' and HRP1513.SGRP = '03')
         join PA0001 on (PA0001."PLANS" = HRP1513.OBJID)

UNION

select V_LIQUID_SRM.PERNR as pernr_atasan,
       PA0001.PERNR         pernr_bawahan,
       PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'SRM') V_LIQUID_SRM
         join M_STRUKTUR_ORGANISASI on (M_STRUKTUR_ORGANISASI.SOBID = V_LIQUID_SRM.orgeh)
         join M_STRUKTUR_POSISI
              on (M_STRUKTUR_POSISI.SOBID = M_STRUKTUR_ORGANISASI.OBJID and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513 on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '04' and HRP1513.SGRP = '03')
         join PA0001 on (PA0001."PLANS" = HRP1513.OBJID)
