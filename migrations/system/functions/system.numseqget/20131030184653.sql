create or replace function system.numseqget(_name varchar)
	returns bigint as
$body$
declare
	rec system.numseq%rowtype;
	result bigint = 0;
begin
	select * into rec from system.numseq where name = _name for update;

	if not found then
		insert into system.numseq(name, value) values(_name, 0) returning * into rec;
	end if;

	if rec is not null then
		update system.numseq set value = value + 1 where name = _name returning value into result;
	end if;

	return result;
end
$body$ language plpgsql;
