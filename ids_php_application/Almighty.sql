insert into COMPONENTS (SERIAL_NR,PARENT_NR, TYPE_ID, SHIPPING_DATE, INSTALLATION_DATE) values (20000,null,3,'25-Jan-22','25-Jan-22');

delete from components where serial_nr in(select * from (select serial_nr from components order by serial_nr desc) where rownum<2);
insert into COMPONENTS (SERIAL_NR, PARENT_NR, TYPE_ID, SHIPPING_DATE, INSTALLATION_DATE) values (4826,1,1,'25-Jan-22',null);
select * from components order by serial_nr desc;

select * from components where serial_nr+1 not in (select serial_nr from components);

select serial_nr from (SELECT serial_nr FROM components ORDER BY dbms_random.value );

drop procedure p_delete_person;

select sum(types.type_cost) from incidents 
join affects on incidents.guid = affects.guid 
join components on components.serial_nr = affects.serial_nr 
join types on components.type_id=types.guid where incidents.guid=1;

select incidents.guid, sum(types.type_cost) from incidents 
left join affects on incidents.guid = affects.guid 
join components on components.serial_nr = affects.serial_nr 
join types on components.type_id=types.guid 
group by incidents.guid;

select avg(cost) from 
(select sum(types.type_cost) as cost from incidents 
left join affects on incidents.guid = affects.guid 
join components on components.serial_nr = affects.serial_nr 
join types on components.type_id=types.guid 
group by incidents.guid);

select components.serial_nr,components.parent_nr, types.kv2,components.shipping_date,components.installation_date,types.type_cost from affects 
join components on components.serial_nr = affects.serial_nr
join types on components.type_id=types.guid
where affects.guid=1;
