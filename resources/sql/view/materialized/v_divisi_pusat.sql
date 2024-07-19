select OBJID, stext, STATUS
from M_STRUKTUR_ORGANISASI
where 
"STATUS" = 'ACTV' AND 
COMPANY_CODE = '1000' and 
(stext like 'DIV%' or stext like 'SPI%' or stext like 'SETPER%')