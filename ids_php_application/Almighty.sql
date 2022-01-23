select * from documents  where a6z like 'A6Z1%';
delete from documents;
delete from employees;
delete from types;

select * from types ;
select count(*) from (select specification_id from types group by specification_id);
select count(*) from documents where az6 like 'AZ61%';

select documents.guid,documents.az6 from documents left join types on documents.guid=types.specification_id where types.guid is null and documents.az6 like 'AZ61%';

select count(*) from (select documents.guid,documents.az6 from documents left join types on documents.guid=types.specification_id where types.guid is null and documents.az6 like 'AZ61%');

insert into types(specification_id,kv2,supplier,type_cost) values (5001,'KV2000000001','Kenco Logistic Services LLC',50.99);
select * from documents where az6 like 'AZ61%';
select * from documents  where guid < 6000;

SELECT * FROM documents WHERE guid LIKE '%622326'
              AND upper(a6z) LIKE upper('%a6z1000%')
              AND upper(document_url) LIKE upper('%2325%');
              
SELECT * FROM documents order by guid desc;

select types.guid,types.specification_id,documents.a6z,types.kv2,types.supplier,types.type_cost from types inner join documents on types.specification_id= documents.guid;