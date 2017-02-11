<?php

namespace Security {
	abstract class AccessMode extends \Enum {
		const Read = 1;
		const Insert = 2;
		const Update = 3;
		const Remove = 4;
		const Execute = 5;
		const View = 6;
	}
}

 