create table security.ldaproles (
	id serial primary key,
	roleid int not null references security.roles(id) on delete cascade on update cascade,
	dn varchar not null unique
) without oids;
