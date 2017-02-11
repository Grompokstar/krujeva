create table reports.reports (
	id bigserial primary key,
	classname varchar not null,
	parameters json,
	status int not null
) without oids;
