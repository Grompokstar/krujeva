DO
$$DECLARE
	r record;
BEGIN
    FOR r IN select * from market.userprofiles LOOP

	update market.barbershop set cityid = r.cityid , lat = r.lat , lng = r.lng where userid = r.userid;

	update market.userprofiles set cityid = null , lat = null , lng = null where id = r.id;

    END LOOP;
END$$;