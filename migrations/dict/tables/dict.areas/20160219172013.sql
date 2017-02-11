create table if not exists dict.areas (
	id serial primary key,
	name varchar not null,
	countryid int not null references dict.countries(id) on delete cascade on update cascade
) without oids;
