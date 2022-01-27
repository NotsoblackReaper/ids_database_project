--Aggr. Function
select AVG(type_cost) AS Average_cost from types;

--Inner Join
select documents.guid as doc_id, documents.az6 as az6, documents.document_url as url, 
types.guid as type_id, types.kv2 as kv2, types.supplier as supplier,types.type_cost as cost 
from types inner join documents on types.specification_id= documents.guid;

--Group by Having
select supplier, count(*) from types group by supplier;
select supplier, count(*) from types group by supplier having count(*)>650;
