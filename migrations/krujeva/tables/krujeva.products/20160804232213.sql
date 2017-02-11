create table if not exists krujeva.products
(
  id bigserial primary key,
  title varchar,
  text varchar,
  price varchar,
  categoryid int not null,
  absolutepath varchar not null,
  relativepath varchar not null,
  name varchar not null,
  width int not null,
  height int not null
);