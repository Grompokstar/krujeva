create table if not exists market.bonusaccruals
(
  id bigserial primary key,
  orderid int not null references market.orders(id) on delete restrict on update cascade,
  clientid int not null references security.users(id) on delete cascade on update cascade,
  bonus int not null,
  utcaccrualdatetime timestamp not null,
  added int not null default 0
);