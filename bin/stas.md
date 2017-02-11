
TRACKING
========

Конфиг: `/etc/glonass/tracking.json`.

Папка логов: `/var/log/glonass/tracking/`.

Сборщик сигналов из Emergency 4.0
---------------------------------

	su -c "php /usr/local/www/stas/emergency/bin/tracking/signals-puller-emergency.php >> /var/log/glonass/tracking/signals-emergency.log 2>&1 &" -s /bin/sh nginx

Сборщик сигналов из МВД
---------------------------------

	su -c "php /usr/local/www/stas/emergency/bin/tracking/signals-puller-mvd.php >> /var/log/glonass/tracking/signals-mvd.log 2>&1 &" -s /bin/sh nginx


CALLCENTER
==========

Конфиг: `/etc/glonass/callcenter.json`.

Папка логов: `/var/log/glonass/callcenter/`.

Путь к сообщениям в gisex: `/var/local/gisex/callcenter/`.
Путь к сообщениям из gisex: `/var/local/gisex/callcenter-pull/`.

Message
-------
	su -c "NODE_PATH=/usr/lib/node_modules node /usr/local/www/stas/emergency/nodejs/message/application.js --mp=10006 --wp=10005 --mode=redis --storage=redis --tag=__SecurityContext >> /var/log/glonass/callcenter/message.log 2>&1 &" -s /bin/sh nginx

GISEX-Agent
-----------
	su -c "php /usr/local/www/stas/emergency/bin/gisex-agent.php --path=/var/local/gisex/callcenter --host=http://gisex.stas.glonass.shire.local --config=/etc/glonass/callcenter.json >> /var/log/glonass/callcenter/gisex-agent.log 2>&1 &" -s /bin/sh nginx

GISEX-Puller
------------
	su -c "php /usr/local/www/stas/emergency/bin/gisex-puller.php --path=/var/local/gisex/callcenter-pull --host=http://gisex.stas.glonass.shire.local --system=callcenter --url=http://callcenter.stas.glonass.shire.local/CallCenter/GISEX/Web/pull --config=/etc/glonass/callcenter.json >> /var/log/glonass/callcenter/gisex-puller.log 2>&1 &" -s /bin/sh nginx

Last signals
------------
	su -c "php /usr/local/www/stas/emergency/bin/periodical.php --app=callcenter --class='CallCenter\\Periodical\\LastSignals' --interval=1 >> /var/log/glonass/callcenter/last-signals.log 2>&1 &" -s /bin/sh nginx

Signals
-------
	su -c "php /usr/local/www/stas/emergency/bin/periodical.php --app=callcenter --class='CallCenter\\Periodical\\Signals' --interval=1 >> /var/log/glonass/callcenter/signals.log 2>&1 &" -s /bin/sh nginx

Отчёты
------
	su -c "php /usr/local/www/stas/emergency/bin/reports.php --app=callcenter >> /var/log/glonass/callcenter/reports.log 2>&1 &" -s /bin/sh nginx

POLICE
======

Конфиг: `/etc/glonass/police.json`.

Папка логов: `/var/log/glonass/police/`.

Путь к сообщениям в gisex: `/var/local/gisex/police/`.
Путь к сообщениям из gisex: `/var/local/gisex/police-pull/`.

Message
-------
	su -c "NODE_PATH=/usr/lib/node_modules node /usr/local/www/stas/emergency/nodejs/message/application.js --mp=10008 --wp=10007 --mode=redis --storage=redis --tag=__SecurityContext >> /var/log/glonass/police/message.log 2>&1 &" -s /bin/sh nginx

GISEX-Agent
-----------
	su -c "php /usr/local/www/stas/emergency/bin/gisex-agent.php --path=/var/local/gisex/police --host=http://gisex.stas.glonass.shire.local --config=/etc/glonass/police.json >> /var/log/glonass/police/gisex-agent.log 2>&1 &" -s /bin/sh nginx

GISEX-Puller
------------
	su -c "php /usr/local/www/stas/emergency/bin/gisex-puller.php --path=/var/local/gisex/police-pull --host=http://gisex.stas.glonass.shire.local --system=police --url=http://police.stas.glonass.shire.local/Police/GISEX/Web/pull --config=/etc/glonass/police.json >> /var/log/glonass/police/gisex-puller.log 2>&1 &" -s /bin/sh nginx

Last signals
------------
	su -c "php /usr/local/www/stas/emergency/bin/periodical.php --app=police --class='Police\\Periodical\\LastSignals' --interval=1 >> /var/log/glonass/police/last-signals.log 2>&1 &" -s /bin/sh nginx

Signals
-------
	su -c "php /usr/local/www/stas/emergency/bin/periodical.php --app=police --class='Police\\Periodical\\Signals' --interval=1 >> /var/log/glonass/police/signals.log 2>&1 &" -s /bin/sh nginx

Отчёты
------
	su -c "php /usr/local/www/stas/emergency/bin/reports.php --app=police >> /var/log/glonass/police/reports.log 2>&1 &" -s /bin/sh nginx

