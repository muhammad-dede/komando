select PA0001.PERNR,
       NIP,
       CNAME                       NAMA,
       posisi_bawahan.STEXT        JABATAN,
       PA0001.GSBER                UNIT_CODE,
       M_BUSINESS_AREA.DESCRIPTION UNIT_NAME,
       PA0001."PLANS",
       ORG.OBJID                   ORGEH1,
       ORG2.OBJID                  ORGEH2,
       ORG3.OBJID                  ORGEH3,
       ORG4.OBJID                  ORGEH4
from M_STRUKTUR_JABATAN
         join PA0001 on (M_STRUKTUR_JABATAN.PERNR = PA0001.PERNR)
         join M_BUSINESS_AREA on (M_BUSINESS_AREA.BUSINESS_AREA = PA0001.GSBER)
         LEFT JOIN M_STRUKTUR_ORGANISASI ORG on (ORG.OBJID = M_STRUKTUR_JABATAN.ORGEH)
         LEFT JOIN M_STRUKTUR_ORGANISASI ORG2 on (ORG2.OBJID = ORG.SOBID)
         LEFT JOIN M_STRUKTUR_ORGANISASI ORG3 on (ORG3.OBJID = ORG2.SOBID)
         LEFT JOIN M_STRUKTUR_ORGANISASI ORG4 on (ORG4.OBJID = ORG3.SOBID)
         LEFT JOIN M_STRUKTUR_POSISI posisi_bawahan
                   on (posisi_bawahan.OBJID = M_STRUKTUR_JABATAN.PLANS and posisi_bawahan.RELAT = 3)
where PA0001.PERSG in (1, 0)
