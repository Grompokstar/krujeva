create table dict.colors (
	id serial primary key,
	name varchar not null,
	hex varchar not null
) without oids;
