/*
 Trigger to update modifieddatetime field when trackable table row modified.
 */
create function trackable.updated()
	returns trigger as
$$
begin
	new.modifieddatetime = current_timestamp;
	return new;
end
$$ language plpgsql;
