<?php

namespace Security\Auth {
	interface IAuth {
		public static function auth($context, $options = []);
	}
}
 