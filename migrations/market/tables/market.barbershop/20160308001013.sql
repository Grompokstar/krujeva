create table if not exists market.barbershop
(
  id bigserial primary key,
  userid int not null references security.users(id) on delete cascade on update cascade,
  address varchar not null
);