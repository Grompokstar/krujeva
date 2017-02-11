create table if not exists dict.cities (
	id serial primary key,
	name varchar not null,
	areaid int not null references dict.areas(id) on delete cascade on update cascade
) without oids;
