create table if not exists krujeva.slider
(
  id bigserial primary key,
  title varchar,
  text varchar,
  price varchar,
  link varchar,
  absolutepath varchar not null,
  relativepath varchar not null,
  name varchar not null,
  width int not null,
  height int not null
);