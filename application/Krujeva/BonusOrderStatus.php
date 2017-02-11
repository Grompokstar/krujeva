<?php

namespace Krujeva {

	class BonusOrderStatus extends \Enum {
		const NewOrder = 1; // новые
		const VerifiedOrder = 2; // подтвержденные
		const CancelledOrder = 3; // отмененные заявки
	}
}
