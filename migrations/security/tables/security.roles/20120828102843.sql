create table security.roles (
	id serial primary key,
	name varchar not null unique,
	description text
) without oids;
