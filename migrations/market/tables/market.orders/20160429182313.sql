alter table market.orders add column dealerregionid int references market.dealerregions(id) on delete set null on update cascade;