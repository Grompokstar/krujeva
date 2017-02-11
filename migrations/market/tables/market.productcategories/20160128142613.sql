create table if not exists market.productcategories
(
  id bigserial primary key,
  name varchar not null,
  code varchar
);