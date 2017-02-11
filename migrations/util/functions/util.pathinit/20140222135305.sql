create or replace function util.pathinit(_tablename varchar)
	returns void as
$body$
declare
	_query text;
	_type name;
	_names text[];
	_schema text;
	_table text;
begin
	_names = string_to_array(_tablename, '.');
	_schema = _names[1];
	_table = _names[2];

	select
			pg_type.typname into _type
		from pg_type
		inner join pg_attribute on pg_attribute.atttypid = pg_type.oid
		inner join pg_class on pg_class.oid = pg_attribute.attrelid
		inner join pg_namespace on pg_namespace.oid = pg_class.relnamespace
			where
				pg_namespace.nspname = _schema and
				pg_class.relname = _table and
				pg_attribute.attname = 'id';

	_query = format('alter table %s add parentid %s references %s(id) on delete cascade on update cascade', _tablename, _type, _tablename);
	execute _query;

	_query = format('alter table %s add path ltree', _tablename);
	execute _query;

	_query = format('create trigger pathupdatebefore before insert or update on %s for each row execute procedure util.pathupdatebeforetrigger()', _tablename);
	execute _query;

	_query = format('create trigger pathupdateafter after update on %s for each row execute procedure util.pathupdateaftertrigger()', _tablename);
	execute _query;
end
$body$ language plpgsql;
