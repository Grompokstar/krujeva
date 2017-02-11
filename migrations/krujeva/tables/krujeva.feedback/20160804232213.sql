create table if not exists krujeva.feedback
(
  id bigserial primary key,
  name varchar,
  phone varchar,
  text varchar,
  datetime timestamp
);