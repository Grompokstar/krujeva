create table if not exists market.dealerprices
(
  id bigserial primary key,
  dealerbrandid int not null references market.dealerbrands(id) on delete cascade on update cascade,
  productid int not null references market.products(id) on delete cascade on update cascade,
  price double precision not null
);