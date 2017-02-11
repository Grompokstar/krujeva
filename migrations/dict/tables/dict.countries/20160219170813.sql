create table if not exists dict.countries (
	id serial primary key,
	name varchar not null
) without oids;
