create or replace function util.partitioninserttrigger()
	returns trigger as
$body$
begin
	execute 'insert into ' || tg_argv[0] || to_char(date_trunc(tg_argv[1], new.datetime), 'YYYYMMDD') || ' values($1.*)' using new;
	return null;
end
$body$ language plpgsql;
