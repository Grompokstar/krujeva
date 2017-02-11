create table if not exists krujeva.events
(
  id bigserial primary key,
  title varchar,
  date date,
  text varchar,
  absolutepath varchar not null,
  relativepath varchar not null,
  name varchar not null,
  width int not null,
  height int not null
);