create table security.ldapsettings (
	id serial primary key,
	name varchar not null unique,
	value varchar not null
) without oids;
