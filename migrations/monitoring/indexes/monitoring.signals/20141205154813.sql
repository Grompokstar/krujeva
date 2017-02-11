create index on monitoring.signals(modifieddatetime);
create index on monitoring.signals USING gist (currentgeog);
create index on monitoring.signals (trackerid, lastcreateddatetime);