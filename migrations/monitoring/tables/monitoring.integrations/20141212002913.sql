create table monitoring.integrations (
	id bigserial primary key,
	ip varchar,
	port int,
	status int not null
) without oids;