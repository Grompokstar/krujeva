<?php

namespace Compressor {

	class Statics {

		public static function css($css = []) {
			$type = 'css';
			$static = new IncludeStatic($type);

			if (!$static->isInit) {
				return static::getLinkCss($css);
			}

			//$static->version = rand(1, 300);

			$name = md5(implode('', $css)). $static->version.'.css';
			$prevname = md5(implode('', $css)) . ($static->version-1). '.css';
			$absolutepath = $static->getSavePath($type);
			$path = $static->getPath($type);

			if (!$static->config['debug'] && file_exists($absolutepath. $name)) {
				return static::getLinkCss([$path . $name]);
			}

			if (!$static->init($type, $name, $css)) {
				return static::getLinkCss($css);
			}

			if (file_exists($absolutepath. $prevname)) {
				@unlink($absolutepath . $prevname);
			}

			return static::getLinkCss([$path . $name]);
		}

		public static function js($js = []) {
			$type = 'js';

			$static = new IncludeStatic($type);

			if (!$static->isInit) {
				return static::getLinkJs($js);
			}

			//$static->version = rand(1, 300);

			$names = '';
			foreach($js  as $url) {
				if(!is_array($url)){
					$names.= $url;
				}else if(isset($url[1])){
					$names .= $url[1];
				}
			}

			$name = md5($names) . $static->version . '.js';
			$prevname = md5($names) . ($static->version - 1) . '.js';
			$absolutepath = $static->getSavePath($type);
			$path = $static->getPath($type);

			if (!$static->config['debug'] && file_exists($absolutepath . $name)) {
				return static::getLinkJs([$path . $name]);
			}

			if (!$static->init($type, $name, $js)) {
				return static::getLinkJs($js);
			}

			if (file_exists($absolutepath . $prevname)) {
				@unlink($absolutepath . $prevname);
			}

			return static::getLinkJs([$path . $name]);
		}

		private static function getLinkJs($js){
			$data = [];

			foreach ($js as $url) {
				if(is_array($url)){
					continue;
				}

				$data[] = '<script src="'. $url.'"></script>';
			}

			return implode('', $data);
		}

		private static function getLinkCss($css){
			$data = [];

			foreach($css as $url){
				$data[] = '<link rel="stylesheet" href="'. $url.'">';
			}

			return implode('', $data);
		}
	}
}