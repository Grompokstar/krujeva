create table if not exists market.orderproducts
(
  id bigserial primary key,
  orderid int not null references market.orders(id) on delete restrict on update cascade,
  productid int not null references market.products(id) on delete restrict on update cascade,
  count int not null,
  price double precision
);