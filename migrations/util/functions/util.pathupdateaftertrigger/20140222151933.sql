create or replace function util.pathupdateaftertrigger()
	returns trigger as
$body$
declare
begin
	perform util.pathupdate(tg_table_schema || '.' || tg_table_name, new.id::bigint, new.path::text);

	return null;
end
$body$ language plpgsql;
