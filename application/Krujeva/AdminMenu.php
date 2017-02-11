<?php

namespace Krujeva {

	class AdminMenu {

		public static function menu() {

			 $pages = [
				 ['url' => '/Admin/slider', 'title' => 'слайдер(главная)', 'active' => ''],
				 ['url' => '/Admin/products', 'title' => 'меню', 'active' => ''],
				 ['url' => '/Admin/events', 'title' => 'мероприятия', 'active' => ''],
				 ['url' => '/Admin/sliderevents', 'title' => 'слайдер(мероприятия)', 'active' => ''],
				 ['url' => '/Admin/news', 'title' => 'новости', 'active' => ''],
				 ['url' => '/Admin/feedback', 'title' => 'обратная связь', 'active' => ''],
			 ];

			$url = explode('?', $_SERVER['REQUEST_URI']);

			$url = $url[0];

			//$url = explode('/', $url);

			//$url = '/'. (isset($url[1]) ? $url[1] : '');

			foreach ($pages as &$page) {

				if ($page['url'] == $url) {
					$page['active'] = 'active';
				}
			}

			return $pages;
		}

	}
}