<?php

namespace Krujeva\Data {

	class SliderEvents extends Record {
		public static $container = ' krujeva.sliderevents';

		public static $fields = [
			'id' => ['int'],
			'title' => ['string'],
			'text' => ['string'],
			'price' => ['string'],
			'link' => ['string'],
			'absolutepath' => ['string'],
			'relativepath' => ['string'],
			'name' => ['string'],
			'width' => ['int'],
			'height' => ['int']
		];

		public static $filter = [
		];

		public static function insert($record, $options = []) {

			static::formRecord($record);

			txbegin();

			//@save photo
			if (isset($record['photo'])) {

				$res = static::savePhoto($record['photo']);

				if (is_array($res)) {
					$record = array_merge($record, $res);
				}
			}

			$item = parent::insert($record, $options);

			txcommit();

			return $item;
		}

		public static function update($record, $options = []) {

			static::formRecord($record);

			txbegin();

			//@save photo
			if (isset($record['photo'])) {

				$res = static::savePhoto($record['photo']);

				if (is_array($res)) {
					$record = array_merge($record, $res);
				}
			}

			$item = parent::update($record, $options);

			txcommit();

			return $item;
		}

		//заполняем поля - если они не были заполнены пользователем
		private static function formRecord(&$record) {

			if (isset($_FILES['file'])) {
				$record['photo'] = $_FILES['file'];
			}

			if (isset($record['date'])) {
				$record['date'] = date('Y-m-d', strtotime($record['date']));
			}
		}

		public static function savePhoto($photo) {

			global $APP_PATH;

			$file = $APP_PATH . '/extensions/Wideimage/WideImage.php';

			if (!file_exists($file)) {
				return null;
			}

			include_once($file);

			$photo = new \Data\UploadFile($photo, ['type' => \Data\UploadFile::$ImageType]);

			if (!$photo->save()) {
				return null;
			}

			$item = [
				'name' => $photo->get('saved_name'),
				'relativepath' => $photo->get('relative_path'),
				'absolutepath' => $photo->get('saved_path')
			];

			$filePath = $item['absolutepath'] . $item['name'];

			$cropData = static::crop($item['absolutepath'], $item['name']);

			if (!$cropData) {

				@unlink($filePath);

				return false;
			}

			$item = array_merge($item, $cropData);

			return $item;
		}

		private static function crop($imageurl, $name) {

			$image = \WideImage::load($imageurl . $name);

			@unlink($imageurl . $name);

			$ext = \Data\UploadFile::getExtension($name);

			//пнг надо в jpg переделать
			if (in_array($ext, ['png', 'gif', 'bmp', 'apng'])) {

				$original = $image;

				$image = \WideImage::createTrueColorImage($original->getWidth(), $original->getHeight());

				$bg = $image->allocateColor(255, 255, 255);

				$image->fill(0, 0, $bg);

				$image->merge($original)->saveToFile($imageurl . $name);

				$image = \WideImage::load($imageurl . $name);

				@unlink($imageurl . $name);
			}

			$name = \Data\UploadFile::randName('jpg');

			if (!$image) {
				return false;
			}

			$data = [
				'width' => $image->getWidth(),
				'height' => $image->getHeight(),
				'name' => $name,
			];

			//@main image
			$maxWidth = 1280;
			$maxHeight = 840;

			if ($image->getWidth() > $image->getHeight() && $image->getWidth() > $maxWidth) {

				$image = $image->resize($maxWidth);

			} else if ($image->getHeight() > $maxHeight) {

				$image = $image->resize(null, $maxHeight);
			}

			/*
			* Save
			*/
			$image->saveToFile($imageurl . $name, 95);

			$data['width'] = $image->getWidth();

			$data['height'] = $image->getHeight();

			return $data;
		}

		public static function split3($items) {

			$limit = 3;
			$index = 0;
			$current = 0;
			$res = [];

			foreach ($items as $item) {

				if ($current >= $limit) {
					$current = 0;
					$index++;
				}

				if (!isset($res[$index])) {
					$res[$index] = [];
				}

				$res[$index][] = $item;

				$current++;
			}

			return $res;
		}
	}
}
