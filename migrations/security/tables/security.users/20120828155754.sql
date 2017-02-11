create table security.users (
	id bigserial primary key,
	roleid int references security.roles(id) on delete cascade on update cascade,
	login varchar not null unique,
	password varchar,
	name varchar,
	authtype int not null,
	employerid int references security.users(id) on delete set null on update cascade
) without oids;
