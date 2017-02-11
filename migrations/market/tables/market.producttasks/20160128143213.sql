create table if not exists market.producttasks
(
  id bigserial primary key,
  name varchar not null,
  code varchar
);