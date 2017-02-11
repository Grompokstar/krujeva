/*
 Trigger to set createddatetime field on trackable table row.
 */
create function trackable.created()
	returns trigger as
$$
begin
	new.createddatetime = current_timestamp;
	return new;
end
$$ language plpgsql;
