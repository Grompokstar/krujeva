alter table market.userprofiles add column regionid int references dict.areas(id) on delete set null on update cascade;



DO
$$DECLARE
	r record;
	area int;
BEGIN
    FOR r IN select * from market.userprofiles LOOP

	select areaid into area from market.barbershop
	inner join dict.cities on cities.id = barbershop.cityid
	where userid = r.userid;

	update market.userprofiles set regionid = area where id = r.id;

    END LOOP;
END$$;