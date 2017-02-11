create table if not exists osm.geoobjects
(
  id bigserial primary key,
  osm_id bigint not null,
  type integer not null,
  regionid bigint,
  districtid bigint,
  placedistrictid bigint,
  placeid bigint,
  streetid bigint,
  name character varying,
  fullname character varying,
  geog geometry(Point,4326)
);