alter table market.userprofiles add column dealerid int references market.dealers(id) on delete cascade on update cascade;