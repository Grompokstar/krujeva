<?php

namespace Krujeva {

	class UserStatus extends \Enum {
		const NotVerified = 1;
		const Verified = 2;
		const Blocked = 3;
		const NotVerifyPhone = 4;
	}
}
