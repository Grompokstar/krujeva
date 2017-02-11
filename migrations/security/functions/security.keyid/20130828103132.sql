create function security.keyid(_name varchar)
	returns int as
$$
declare
	key security.keys%rowtype;
begin
	select * into key from security.keys where name = _name;

	if found then
		return key.id;
	else
		return null;
	end if;
end
$$ language plpgsql;
