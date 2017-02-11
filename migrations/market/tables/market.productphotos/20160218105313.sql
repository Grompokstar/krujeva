create table if not exists market.productphotos
(
  id bigserial primary key,
  productid int not null references market.products(id) on delete cascade on update cascade,
  absolutepath varchar not null,
  relativepath varchar not null,
  name varchar not null,
  avatarname varchar not null,
  width int not null,
  height int not null
);