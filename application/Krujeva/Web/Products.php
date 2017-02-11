<?php

namespace Krujeva\Web {

	class Products extends DataController {
		protected $record = 'Krujeva\Data\Products';
		protected $securityKey = 'Data.Krujeva.Products';

		protected static $__actions = ['insert', 'remove', 'update'];
	}
}
 