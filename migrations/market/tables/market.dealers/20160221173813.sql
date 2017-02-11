create table if not exists market.dealers
(
  id bigserial primary key,
  name varchar not null,
  cityid int not null references dict.cities(id) on delete restrict on update cascade,
  address varchar,
  phone varchar
);