create table security.urls (
	id serial primary key,
	url varchar not null,
	keyid int not null references security.keys(id) on delete cascade on update cascade,
	access int not null
) without oids;
