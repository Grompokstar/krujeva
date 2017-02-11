create table security.ldapusers (
	id bigserial primary key,
	userid int not null references security.users(id) on delete cascade on update cascade,
	accountname varchar not null unique
) without oids;
