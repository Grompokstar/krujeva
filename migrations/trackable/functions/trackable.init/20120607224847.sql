/*
 Initialize trackable table.
 @param varchar _name Table name.
 @param boolean _log If true then modifications will be logged.
 */
create or replace function trackable.init(_name varchar, _log boolean default true)
	returns void as
$$
declare
	logtablename text;
	fields text[];
	fieldname varchar;
	fieldtype varchar;
	nameparts text[];
begin
	execute 'alter table ' || _name || ' add createddatetime timestamp with time zone, add modifieddatetime timestamp with time zone, add creator json, add modifier json';

	execute 'create trigger trackablecreated before insert on ' || _name || ' for each row execute procedure trackable.created()';
	execute 'create trigger trackablemodified before update on ' || _name || ' for each row execute procedure trackable.updated()';

	if _log then
		nameparts = string_to_array(_name, '.');
		logtablename = _name || 'log';

		for fieldname, fieldtype in
			select
					pg_attribute.attname attname, pg_type.typname typname
				from pg_attribute
				inner join pg_class on pg_class.oid = pg_attribute.attrelid
				inner join pg_namespace on pg_namespace.oid = pg_class.relnamespace
				inner join pg_type on pg_attribute.atttypid = pg_type.oid
					where
						pg_namespace.nspname = nameparts[1] and
						pg_class.relname = nameparts[2] and
						pg_attribute.atttypid > 0 and
						pg_attribute.attnum > 0
					order by
						pg_attribute.attnum
		loop
			fields = array_append(fields, fieldname || ' ' || fieldtype);
		end loop;

		fields = array_prepend('recorddatetime timestamptz default current_timestamp', fields);
		fields = array_prepend('recordoperation text', fields);

		execute 'create table ' || logtablename || '(' || array_to_string(fields, ', ') || ')';
		execute 'grant all on table ' || logtablename || ' to public';
		execute 'create trigger trackablelog_insert_update after insert or update on ' || _name || ' for each row execute procedure trackable.log()';
		execute 'create trigger trackablelog_delete before delete on ' || _name || ' for each row execute procedure trackable.log()';
	end if;

	execute 'update ' || _name || ' set createddatetime = current_timestamp';
end
$$ language plpgsql;
