create table if not exists market.propertyvalues
(
  id bigserial primary key,
  productpropertyid int not null references market.productproperties(id) on delete restrict on update cascade,
  productid int not null references market.products(id) on delete cascade on update cascade,
  listpropertyid int references market.listpropertyvalues(id) on delete cascade on update cascade,
  valuestring varchar,
  valueint int,
  valuedecimal double precision
);