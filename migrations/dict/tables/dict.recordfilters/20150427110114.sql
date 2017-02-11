create table dict.recordfilters (
	id serial primary key,
	subjectid int not null,
	subjecttype int not null,
	record varchar not null,
	relationclass varchar not null,
	relationalias varchar not null,
	include int[],
	exclude int[],
	options json
) without oids;
