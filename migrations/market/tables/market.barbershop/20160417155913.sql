alter table market.barbershop add column cityid int references dict.cities(id) on delete set null on update cascade;
alter table market.barbershop add column lat double precision;
alter table market.barbershop add column lng double precision;