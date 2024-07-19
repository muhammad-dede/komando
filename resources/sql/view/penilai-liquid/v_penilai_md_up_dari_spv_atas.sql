select ATASAN.PERNR  as pernr_atasan,
       BAWAHAN.PERNR as pernr_bawahan,
       BAWAHAN.SNAME
from (select * from v_liquid_atasan where jabatan = 'MD_UP') ATASAN
         join (select * from v_liquid_atasan where jabatan = 'SPV_ATAS_SUP') BAWAHAN
              on (ATASAN.GSBER = BAWAHAN.GSBER)
