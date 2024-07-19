select V_LIQUID_EVP.PERNR as pernr_atasan,
       PA0001.PERNR          pernr_bawahan,
       PA0001.SNAME
from (select * from v_liquid_atasan where jabatan = 'EVP') V_LIQUID_EVP
         join M_STRUKTUR_POSISI on (M_STRUKTUR_POSISI.SOBID = V_LIQUID_EVP.orgeh and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513 on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '07' and HRP1513.SGRP >= '15' AND HRP1513.SGRP <= '17')
         join PA0001 on (PA0001."PLANS" = HRP1513.OBJID)

UNION

select V_LIQUID_EVP.PERNR as pernr_atasan,
       PA0001.PERNR          pernr_bawahan,
       PA0001.SNAME
from  (select * from v_liquid_atasan where jabatan = 'EVP') V_LIQUID_EVP
         join M_STRUKTUR_ORGANISASI on (M_STRUKTUR_ORGANISASI.SOBID = V_LIQUID_EVP.orgeh)
         join M_STRUKTUR_POSISI
              on (M_STRUKTUR_POSISI.SOBID = M_STRUKTUR_ORGANISASI.OBJID and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513 on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '07' and HRP1513.SGRP >= '15' AND HRP1513.SGRP <= '17')
         join PA0001 on (PA0001."PLANS" = HRP1513.OBJID)

UNION

SELECT V_LIQUID_EVP.PERNR as pernr_atasan,
       PA0001.PERNR          pernr_bawahan,
       PA0001.SNAME
from  (select * from v_liquid_atasan where jabatan = 'EVP') V_LIQUID_EVP
         join M_STRUKTUR_ORGANISASI on (M_STRUKTUR_ORGANISASI.SOBID = V_LIQUID_EVP.orgeh)
         join M_STRUKTUR_ORGANISASI M_ORG2 on (M_ORG2.SOBID = M_STRUKTUR_ORGANISASI.OBJID)
         join M_STRUKTUR_POSISI
              on (M_STRUKTUR_POSISI.SOBID = M_ORG2.OBJID and M_STRUKTUR_POSISI.RELAT = 3)
         join HRP1513 on (HRP1513.OBJID = M_STRUKTUR_POSISI.OBJID and HRP1513.MGRP = '07' and HRP1513.SGRP >= '15' AND HRP1513.SGRP <= '17')
         join PA0001 on (PA0001."PLANS" = HRP1513.OBJID)

