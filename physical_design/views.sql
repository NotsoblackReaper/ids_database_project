--Aggr. Function
create view aggr_func as select AVG(type_cost) AS Average_cost from types;

--Inner Join
create view inner_join as select documents.guid as doc_id, documents.az6 as az6, documents.document_url as url, 
types.guid as type_id, types.kv2 as kv2, types.supplier as supplier,types.type_cost as cost 
from types inner join documents on types.specification_id= documents.guid;

--Group by Having
create view group_by as select supplier as Supplier, count(*) as Types from types group by supplier having count(*)>650;


select * from aggr_func;
select * from inner_join;
select * from group_by;