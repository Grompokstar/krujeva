create function trackable.log()
	returns trigger as
$body$
	
declare
	logtablename text;
	rec record;
	fields text[];
	inserts text[];
begin
	logtablename = tg_table_schema || '.' || tg_table_name || 'log';

	for rec in
		select
				pg_attribute.attname as name
			from pg_attribute
			inner join pg_class on pg_class.oid = pg_attribute.attrelid
			inner join pg_namespace on pg_namespace.oid = pg_class.relnamespace
				where
					pg_namespace.nspname = tg_table_schema::varchar and
					pg_class.relname = tg_table_name::varchar and
					pg_attribute.atttypid > 0 and
					pg_attribute.attnum > 0
				order by
					pg_attribute.attnum
	loop
		fields = array_append(fields, rec.name::text);
		inserts = array_append(inserts, '$1.' || rec.name);
	end loop;

	fields = array_prepend('recordoperation', fields);
	inserts = array_prepend(quote_nullable(tg_op), inserts);

	if tg_op = 'INSERT' or tg_op = 'UPDATE' then
		execute 'insert into ' || logtablename || '(' || array_to_string(fields, ',') || ')' || ' values(' || array_to_string(inserts, ',') || ')' using new;
		return new;
	else
		execute 'insert into ' || logtablename || '(' || array_to_string(fields, ',') || ')' || ' values(' || array_to_string(inserts, ',') || ')' using old;
		return old;
	end if;
end

$body$ language plpgsql;
