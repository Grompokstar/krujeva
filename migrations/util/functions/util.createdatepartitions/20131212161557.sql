create function util.createdatepartitions(_table text, _fromdate date, _interval text, _commands text)
	returns int as
$body$
declare
	_names text[];
	_tablename text;
	_schemaname text;
	_currentdate date;
	_todate date;
	_nextdate date;
	_partitionname text;
	_partition text;
	_partitioncommands text;
	rec pg_tables%rowtype;
begin
	_names = string_to_array(_table, '.');

	if array_length(_names, 1) = 1 then
		_schemaname = 'public';
		_tablename = _names[1];
	else
		_schemaname = _names[1];
		_tablename = _names[2];
	end if;

	_currentdate = date_trunc(_interval, _fromdate);
	_todate = date_trunc(_interval, current_date) + ('1 ' || _interval)::interval;

	while _currentdate <= _todate
	loop
		_nextdate = _currentdate + ('1 ' || _interval)::interval;

		_partitionname = _tablename || to_char(_currentdate, 'YYYYMMDD');
		_partition = _schemaname || '.' || _partitionname;

		select * into rec from pg_tables where pg_tables.schemaname = _schemaname and pg_tables.tablename = _partitionname;

		if not found then
			raise notice 'create partition %', _partition;

			execute 'create table ' || _partition || ' (check (datetime >= ' || quote_literal(_currentdate::text) || ' and datetime < ' || quote_literal(_nextdate::text) || ')) inherits (' || _table || ');';
			execute 'grant all on ' || _partition || ' to public';

			if _commands is not null then
				_partitioncommands = format(_commands, _partition);
				execute _partitioncommands;
			end if;
		end if;

		_currentdate = _nextdate;
	end loop;

	execute 'drop trigger if exists zzz_inserttrigger on ' || _table;
	execute 'create trigger zzz_inserttrigger before insert on ' || _table || ' for each row execute procedure util.partitioninserttrigger(' || quote_literal(_table) || ', ' || quote_literal(_interval) || ')';

	return 1;
end
$body$ language plpgsql;
