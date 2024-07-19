select *
from (
         select PENGUKURAN_PERTAMA.*, ROW_NUMBER() over (partition by LIQUID_PESERTA_ID order by CREATED_AT desc) as rn
         from PENGUKURAN_PERTAMA
     )
where rn = 1
