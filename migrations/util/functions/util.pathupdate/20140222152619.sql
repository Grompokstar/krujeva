create or replace function util.pathupdate(_tablename text, _parentid bigint, _parentpath text)
	returns void as
$body$
declare
	rec record;
	_path text;
-- 	_type name;
-- 	_names text[];
-- 	_schema text;
-- 	_table text;
	_query text;
begin
-- 	_names = string_to_array(_tablename, '.');
-- 	_schema = _names[1];
-- 	_table = _names[2];

-- 	select
-- 			pg_type.typname into _type
-- 		from pg_type
-- 		inner join pg_attribute on pg_attribute.atttypid = pg_type.oid
-- 		inner join pg_class on pg_class.oid = pg_attribute.attrelid
-- 		inner join pg_namespace on pg_namespace.oid = pg_class.relnamespace
-- 			where
-- 				pg_namespace.nspname = _schema and
-- 				pg_class.relname = _table and
-- 				pg_attribute.attname = 'parentid';

	_path = _parentpath || '.';
	_query = 'update ' || _tablename || ' set path = (' || quote_literal(_path) || ' || id)::ltree where parentid = ' || _parentid || ' and path <> (' || quote_literal(_path) || ' || id)::ltree returning id, path';

	for rec in execute _query
	loop
		perform util.pathupdate(_tablename, rec.id::bigint, rec.path::text);
	end loop;

-- 	for rec in execute 'select * from ' || _tablename || ' where parentid = ' || _parentid
-- 	loop
-- 		execute 'update ' || _tablename || ' set path = ' ||  || '($1 || ''.'' || $2::text)::ltree where id = $2::' || _type || ' and path <> ($1 || ''.'' || $2::text)::ltree returning path' using _parentpath, rec.id into _path;
--
-- 		perform util.pathupdate(_tablename, rec.id::bigint, _path);
-- 	end loop;
end
$body$ language plpgsql;
