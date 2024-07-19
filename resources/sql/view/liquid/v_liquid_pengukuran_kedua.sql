select *
from (
         select PENGUKURAN_KEDUA.*, ROW_NUMBER() over (partition by LIQUID_PESERTA_ID order by CREATED_AT desc) as rn
         from PENGUKURAN_KEDUA
     )
where rn = 1
