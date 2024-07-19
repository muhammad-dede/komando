select PERNR,
       PLANS,
       ORGEH,
       GSBER,
       BUKRS,
       BTRTL,
       SNAME,
       'EVP' as JABATAN
from PA0001
where BUKRS = '1000'
  and PLANS in (select objid from HRP1513 where MGRP = '07' and SGRP >= '21' AND SGRP <= '23')
  and PERSG in (0, 1)
union
select PERNR,
       PLANS,
       ORGEH,
       GSBER,
       BUKRS,
       BTRTL,
       SNAME,
       'GM' as JABATAN
from PA0001
where BUKRS <> '1000'
  and PLANS in (select objid from HRP1513 where MGRP = '04' and SGRP = '01')
  and PERSG in (0, 1)
union
select PERNR,
       PLANS,
       ORGEH,
       GSBER,
       BUKRS,
       PA0001.BTRTL BTRTL,
       SNAME,
       'MD_PUSAT' as JABATAN
from PA0001
join m_level_unit on (m_level_unit."level" = '1' and PA0001.WERKS = m_level_unit.werks and PA0001.BTRTL = m_level_unit.btrtl)
where PLANS in (select objid from HRP1513 where MGRP = '04' and SGRP = '03')
  and PERSG in (0, 1)
union
select PERNR,
       PLANS,
       ORGEH,
       GSBER,
       BUKRS,
       PA0001.BTRTL BTRTL,
       SNAME,
       'MD_UP' as JABATAN
from PA0001
join m_level_unit on (m_level_unit."level" = '2' and PA0001.WERKS = m_level_unit.werks and PA0001.BTRTL = m_level_unit.btrtl)
where PLANS in (select objid from HRP1513 where MGRP = '04' and SGRP = '03')
  and PERSG in (0, 1)
union
select PERNR,
       PLANS,
       ORGEH,
       GSBER,
       BUKRS,
       BTRTL,
       SNAME,
       'SPV_ATAS_SUP' as JABATAN
from PA0001
where PLANS in (select objid from HRP1513 where MGRP = '04' and SGRP = '04')
  and WERKS in (select werks from m_level_unit where M_LEVEL_UNIT."level" = '3')
  and BTRTL in (select btrtl from m_level_unit where M_LEVEL_UNIT."level" = '3')
  and PERSG in (0, 1)
union
select PERNR,
       PLANS,
       ORGEH,
       GSBER,
       BUKRS,
       BTRTL,
       SNAME,
       'SRM' as JABATAN
from PA0001
where BUKRS <> '1000'
  and PLANS in (select objid from HRP1513 where MGRP = '04' and SGRP = '02')
  and PERSG in (0, 1)
union
select PERNR,
       PLANS,
       ORGEH,
       GSBER,
       BUKRS,
       BTRTL,
       SNAME,
       'VP' as JABATAN
from PA0001
where BUKRS = '1000'
  and PLANS in (select objid from HRP1513 where MGRP = '07' and SGRP >= '18' AND SGRP <= '20')
  and PERSG in (0, 1)
