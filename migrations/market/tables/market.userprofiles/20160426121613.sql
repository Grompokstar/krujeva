DO
$$DECLARE
	r record;
	barbershopid int;
BEGIN
    FOR r IN select * from market.userprofiles LOOP

      select id into barbershopid from market.barbershop
        where userid = r.userid limit 1;

      update market.barbershop set salonname = r.salonname where id = barbershopid;

    END LOOP;
END$$;

alter table market.userprofiles drop column salonname;