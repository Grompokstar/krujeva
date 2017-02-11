<?php

namespace Krujeva\Data {

	class ProductPhotos extends Record {
		public static $container = 'market.productphotos';

		public static $fields = [
			'id' => ['int'],
			'productid' => ['int'],
			'absolutepath' => ['string'],
			'relativepath' => ['string'],
			'name' => ['string'],
			'avatarname' => ['string'],
			'width' => ['int'],
			'height' => ['int']
		];

		public static $filter = [
		];

		public static function removePhoto($productid) {

			$item = static::firstBy(['productid' => $productid]);

			if (!$item) {
				return;
			}

			//@remove
			@unlink($item['absolutepath']. $item['avatarname']);

			@unlink($item['absolutepath']. '2x_' .$item['avatarname']);

			@unlink($item['absolutepath']. $item['name']);

			static::remove($item['id']);
		}

		public static function saveOldPhoto($oldproductid, $productid) {

			$old = static::firstBy(['productid' => $oldproductid]);

			if (!$old) {

				return;
			}

			$newName = \Data\UploadFile::randName('jpg');

			$newAvaName = 'av_' .$newName;

			global $APP_PATH;

			$file = $APP_PATH . '/extensions/Wideimage/WideImage.php';

			if (!file_exists($file)) {
				return null;
			}

			include_once($file);

			$data = [
				'productid' => $productid,
				'absolutepath' => $old['absolutepath'],
				'relativepath' => $old['relativepath'],
				'name' => $newName,
				'avatarname' => $newAvaName,
				'width' => $old['width'],
				'height' => $old['height']
			];

			//image
			$image = \WideImage::load($old['absolutepath'] . $old['name']);
			$image->saveToFile($data['absolutepath'] . $data['name']);

			//ava
			$image = \WideImage::load($old['absolutepath'] . $old['avatarname']);
			$image->saveToFile($data['absolutepath'] . $data['avatarname']);

			//ava 2x
			$image = \WideImage::load($old['absolutepath'] . '2x_'. $old['avatarname']);
			$image->saveToFile($data['absolutepath'] . '2x_' .$data['avatarname']);

			return static::insert($data);
		}

		public static function savePhoto($photo, $cropData, $productid) {

			global $APP_PATH;

			$file = $APP_PATH . '/extensions/Wideimage/WideImage.php';

			if (!file_exists($file)) {
				return null;
			}

			include_once($file);

			$photo = new \Data\UploadFile($photo, [
				'type' => \Data\UploadFile::$ImageType
			]);

			if (!$photo->save()) {
				return null;
			}

			$item = [
				'name' => $photo->get('saved_name'),
				'relativepath' => $photo->get('relative_path'),
				'absolutepath' => $photo->get('saved_path'),
			];

			$filePath = $item['absolutepath'] . $item['name'];

			$cropData = static::crop($item['absolutepath'], $item['name'], $cropData);

			if (!$cropData) {

				@unlink($filePath);

				return false;
			}

			$item = array_merge($item, $cropData);

			$item['productid'] = $productid;

			return static::insert($item);
		}

		private static function crop($imageurl, $name, $cropData) {

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

			$avatarImage = $image;

			$data = [
				'width' => $image->getWidth(),
				'height' => $image->getHeight(),
				'name' => $name,
				'avatarname' => 'av_' . $name
			];

			$originalScale = (int)($cropData['currentWidth'] * 100 / $image->getWidth());

			$scale = ((100 + (100 - $originalScale)) / 100);

			//scale mode
			if ($cropData['scale'] !== 100) {

				$copyImage = $image;

				//@background
				$avaWidth = (int) ($cropData['width'] * $scale);

				$avaImage = \WideImage::createTrueColorImage($avaWidth, $avaWidth);

				$bg = $avaImage->allocateColor(255, 255, 255);

				$avaImage->fill(0, 0, $bg);


				//@scale image
				$scaleWidth = (int)($cropData['currentWidth'] * ($cropData['scale'] / 100) * $scale);

				$copyImage = $copyImage->resize($scaleWidth);

				//@merge images
				$avatarImage = $avaImage->merge($copyImage, ($cropData['offsetLeft'] - $cropData['x']), ($cropData['offsetTop'] - $cropData['y']));

			} else {

				/*
				* AVATAR
				* CROP
				*/
				if ($avatarImage->getWidth() > $cropData['currentWidth']) {

					$avatarImage = $avatarImage->resize($cropData['currentWidth']);
				}

				$avatarImage = $avatarImage->crop($cropData['x'], $cropData['y'], $cropData['width'], $cropData['width']);
			}

			/*
			*  @2x - avatar
			*/
			if ($avatarImage->getWidth() > 120) {

				$avatarImage = $avatarImage->resize(120);
			}

			//@2x
			$avatarImage->saveToFile($imageurl . '2x_' . $data['avatarname'], 100);

			//@1x
			if ($avatarImage->getWidth() > 60) {

				$avatarImage = $avatarImage->resize(60);
			}

			//@1x
			$avatarImage->saveToFile($imageurl . $data['avatarname'], 100);



			//@main image
			if ($image->getWidth() > $image->getHeight() && $image->getWidth() > 640) {

				$image = $image->resize(640);

			} else if ($image->getHeight() > 640) {

				$image = $image->resize(null, 640);
			}

			/*
			* Save
			*/
			$image->saveToFile($imageurl . $name, 95);

			$data['width'] = $image->getWidth();

			$data['height'] = $image->getHeight();

			return $data;
		}

		public static function insert($record, $options = []) {

			static::formRecord($record);

			$record = parent::insert($record, $options);

			return $record;
		}

		public static function update($record, $options = []) {

			static::formRecord($record);

			$record = parent::update($record, $options);

			return $record;
		}

		public static function remove($id, $options = []) {

			$record = parent::remove($id, $options);

			return $record;
		}

		public static function build(&$item, $options = []) {

			static::process($item, $options, function (&$item) {
			});

			return $item;
		}

		//заполняем поля - если они не были заполнены пользователем
		private static function formRecord(&$record) {

		}
	}
}
