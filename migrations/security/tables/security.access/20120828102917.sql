create table security.access (
	id serial primary key,
	roleid int not null references security.roles(id) on delete cascade on update cascade,
	keyid int not null references security.keys(id) on delete cascade on update cascade,
	mode int[] not null
) without oids;
