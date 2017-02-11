create table system.numseq (
	id serial primary key,
	name varchar not null,
	value bigint
) without oids;
