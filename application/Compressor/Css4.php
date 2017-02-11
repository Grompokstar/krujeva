<?php

namespace Compressor {

	class Css4 {
		protected $variables;
		protected $openedContainer = false;

		protected $colors = [
			'red' => '#FF0000',
			'green' => '#008000',
			'blue' => '#0000FF'
		];

		public function form(&$css) {
			$this->setVariables($css);
			$this->changeVariables($css);

			/*
			 *  color(red opacity(.1))
			 */
			$this->findColorOpacity($css);
		}

		/*
		 * $options  =
		 * 			returnpos = return content and positions
		 * 			startPos => 1 = startFind Position
		 */
		private function find(&$text, $find, $findFrom, $findEnd, $options = []) {

			$lastPos = 0;
			$positions = [];

			if (isset($options['startPos'])) {

				$lastPos = $options['startPos'];
			}

			while (($lastPos = strpos($text, $find, $lastPos)) !== false) {
				$positions[] = $lastPos;
				$lastPos = $lastPos + strlen($find);
			}

			$content = '';

			//many from pos
			foreach ($positions as $fromPos) {

				$endPos = strpos($text, $findEnd, $fromPos);

				if ($endPos === false) {
					continue;
				}

				$cnt = substr($text, $fromPos, $endPos - $fromPos + 1);

				if ($findFrom) {
					$fromPosf = strpos($cnt, $findFrom);

					if ($fromPosf === false) {
						$cnt = null;
					}

					$cnt = substr($cnt, $fromPosf + 1);
				}

				$content .= $cnt;

				if (in_array('returnpos', $options)) {

					return ['content' => $content, 'fromPos' => $fromPos, 'endPos' => $endPos];
				}
			}

			return $content;
		}

		private function value($text, $find) {
			$value = explode($find, $text);

			if (isset($value[1])) {
				$value = $this->clear($value[1]);
			} else {
				$value = null;
			}

			return $value;
		}

		private function clear($value, $chars = ['"', ';', "'", ":", ")"]) {
			return trim(str_replace($chars, '', $value));
		}

		private function setVariables($css) {

			$rootContainer = $this->find($css, ':root', '{', '}');

			if (!$rootContainer) {
				return;
			}

			$rootContainer = explode(';', $rootContainer);

			foreach ($rootContainer as $element) {

				$variable = $this->find($element, '--', null, ':');

				$variable = $this->clear($variable);

				if (!$variable) {
					continue;
				}

				$value = $this->value($element, $variable);

				if (!$value) {
					continue;
				}

				$this->variables[trim($variable)] = trim($value);
			}
		}


		private function changeVariables(&$css) {
			$variableData = true;
			$startPos = 0;

			while ($variableData) {

				$variableData = $this->find($css, 'var(', null,')', ['returnpos', 'startPos' => $startPos]);

				if (!$variableData) {
					$variableData = null;
					return;
				}

				$variable = $this->find($variableData['content'], 'var(', '(', ')');

				$variable = $this->clear($variable, [')']);

				if (!isset($this->variables[$variable])) {

					$value = $this->clear($variableData['content'], [')']).'__not__found)';

				} else {

					$value = $this->variables[$variable];

				}

				$newValueLength = mb_strlen($value, 'utf-8');
				$oldValueLength = mb_strlen($variableData['content'], 'utf-8');

				$startPos = $variableData['endPos'] + $newValueLength - $oldValueLength;

				$endPos = $variableData['endPos'] - $variableData['fromPos'] + 1;

				$css = substr_replace($css, $value, $variableData['fromPos'], $endPos);
			}
		}

		private function findColorOpacity(&$css) {
			$startPos = 0;
			$while = true;

			while ($while) {

				$colorData = $this->find($css, 'color(', null, ';', ['returnpos', 'startPos' => $startPos]);

				if (!$colorData) {
					$while = false;
					return;
				}

				$opacity = $this->find($colorData['content'], 'opacity(', '(', ')');

				if (!$opacity) {
					$while = false;
					return;
				}

				$opacity = $this->clear($opacity);

				if ($opacity[0] == '.') {
					$opacity = '0' . $opacity;
				}

				$color = $this->find($colorData['content'], 'color(', '(', ' ');
				$color = $this->clear($color);
				$color = $this->color($color);

				$value = $this->hex2rgba($color, $opacity);

				$newValueLength = mb_strlen($value, 'utf-8');
				$oldValueLength = mb_strlen($colorData['content'], 'utf-8');

				$startPos = $colorData['endPos'] + $newValueLength - $oldValueLength;

				$endPos = $colorData['endPos'] - $colorData['fromPos'];

				$css = substr_replace($css, $value, $colorData['fromPos'], $endPos);
			}
		}

		private function color($color) {
			return isset($this->colors[$color]) ? $this->colors[$color] : $color;
		}

		private function hex2rgba($color, $opacity = false) {

			$default = 'rgb(0,0,0)';

			//Return default if no color provided
			if (empty($color)) {
				return $default;
			}

			//Sanitize $color if "#" is provided
			if ($color[0] == '#') {
				$color = substr($color, 1);
			}

			//Check if color has 6 or 3 characters and get values
			if (strlen($color) == 6) {
				$hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
			} elseif (strlen($color) == 3) {
				$hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
			} else {
				return $default;
			}

			//Convert hexadec to rgb
			$rgb = array_map('hexdec', $hex);

			//Check if opacity is set(rgba or rgb)
			if ($opacity) {
				if (abs($opacity) > 1) {
					$opacity = 1.0;
				}
				$output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
			} else {
				$output = 'rgb(' . implode(",", $rgb) . ')';
			}

			//Return rgb(a) color string
			return $output;
		}

	}
}