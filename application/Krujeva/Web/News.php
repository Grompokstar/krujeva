<?php

namespace Krujeva\Web {

	class News extends DataController {
		protected $record = 'Krujeva\Data\News';
		protected $securityKey = 'Data.Krujeva.News';

		protected static $__actions = ['insert', 'remove', 'update'];
	}
}
 