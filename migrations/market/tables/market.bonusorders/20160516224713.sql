create table if not exists market.bonusorders
(
  id bigserial primary key,
  status int not null,
  clientid int not null references security.users(id) on delete cascade on update cascade,
  comment varchar,
  isnew int default 1,
  totalprice double precision,
  phone varchar,
  barbershopid int not null references market.barbershop(id) on delete restrict on update cascade,
  createddatetime timestamp,
  localcreateddatetime timestamp,
  localdeliverydate date
);