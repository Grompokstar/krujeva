<?php

namespace Krujeva {

	class MainMenu {

		public static function menu() {

			 $pages = [
				 ['url' => '/', 'title' => 'о ресторане', 'active' => ''],
				 ['url' => '/menu', 'title' => 'меню', 'active' => ''],
				 ['url' => '/events', 'title' => 'мероприятия', 'active' => ''],
				 ['url' => '/news', 'title' => 'новости', 'active' => ''],
				 ['url' => '/contacts', 'title' => 'контакты', 'active' => ''],
			 ];

			$url = explode('?', $_SERVER['REQUEST_URI']);

			$url = $url[0];

			$url = explode('/', $url);

			$url = '/'. (isset($url[1]) ? $url[1] : '');

			foreach ($pages as &$page) {

				if ($page['url'] == $url) {
					$page['active'] = 'active';
				}
			}

			return $pages;
		}

	}
}