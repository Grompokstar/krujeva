create table if not exists market.dealerregions
(
  id bigserial primary key,
  dealerid int not null references market.dealers(id) on delete cascade on update cascade,
  areaid int not null references dict.areas(id) on delete restrict on update cascade,
  deliveryday int,
  minsum int
);