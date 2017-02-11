create table if not exists market.listpropertyvalues
(
  id bigserial primary key,
  productpropertyid int not null references market.productproperties(id) on delete restrict on update cascade,
  valuestring varchar,
  valueint int,
  valuedecimal double precision
);