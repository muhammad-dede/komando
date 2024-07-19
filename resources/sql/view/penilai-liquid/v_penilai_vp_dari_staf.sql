select V_LIQUID_VP.PERNR as pernr_atasan,
       PA0001.PERNR         pernr_bawahan,
       PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'VP') V_LIQUID_VP
         join M_STRUKTUR_POSISI on (M_STRUKTUR_POSISI.SOBID = V_LIQUID_VP.orgeh and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513 on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '05')
         join PA0001 on (PA0001."PLANS" = HRP1513.OBJID)
