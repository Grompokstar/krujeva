create table if not exists market.userprofiles
(
  id bigserial primary key,
  userid int not null references security.users(id) on delete cascade on update cascade,
  phone varchar,
  organizationname varchar,
  salonname varchar,
  inn varchar,
  cityid int references dict.cities(id) on delete cascade on update cascade,
  status int
);