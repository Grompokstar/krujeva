create table if not exists market.productproperties
(
  id bigserial primary key,
  categoryid int references market.productcategories(id) on delete restrict on update cascade,
  name varchar not null,
  code varchar not null,
  datatype int not null,
  isenum int default 0
);