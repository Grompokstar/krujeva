<?php

namespace Krujeva {

	class OrderStatus extends \Enum {
		const NewOrder = 1; // новые
		const VerifiedOrder = 2; // подтвержденные
		const CancelledOrder = 3; // отмененные заявки
	}
}
