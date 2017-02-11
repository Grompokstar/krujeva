create table if not exists market.dealerbrands
(
  id bigserial primary key,
  dealerregionid int not null references market.dealerregions(id) on delete cascade on update cascade,
  brandid int not null references market.brands(id) on delete cascade on update cascade
);