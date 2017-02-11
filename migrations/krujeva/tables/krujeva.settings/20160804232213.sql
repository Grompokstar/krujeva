create table if not exists krujeva.settings
(
  id bigserial primary key,
  name varchar,
  value varchar
);