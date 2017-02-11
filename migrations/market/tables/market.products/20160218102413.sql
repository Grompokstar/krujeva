create table if not exists market.products
(
  id bigserial primary key,
  productcategoryid int not null references market.productcategories(id) on delete restrict on update cascade,
  producttaskid int references market.producttasks(id) on delete set null on update cascade,
  brandid int not null references market.brands(id) on delete restrict on update cascade
);