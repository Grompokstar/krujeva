create or replace function util.pathupdatebeforetrigger()
	returns trigger as
$body$
declare
	parentpath text;
	_tablename text;
begin
	if new.parentid is not null then
		_tablename = tg_table_schema || '.' || tg_table_name;

		execute 'select path::text from ' || _tablename || ' where id = $1' using new.parentid into parentpath;

		new.path = parentpath || '.' || new.id::text;
	else
		new.path = new.id::text;
	end if;

	return new;
end
$body$ language plpgsql;
