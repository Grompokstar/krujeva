create table if not exists market.bonusorderproducts
(
  id bigserial primary key,
  bonusorderid int not null references market.bonusorders(id) on delete restrict on update cascade,
  productid int not null references market.products(id) on delete restrict on update cascade,
  count int not null,
  price double precision
);