create table security.keys(
	id serial primary key,
	name varchar not null,
	description text
) without oids;
