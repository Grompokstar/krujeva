<?php

namespace System {
	abstract class Code extends \Enum {
		const AccessDenied = 1;
		const QueryFailed = 2;
		const MoveUploadedFailed = 3;
		const InsertFailed = 4;
		const UpdateFailed = 5;
		const RemoveFailed = 6;
		const FileNotExist = 7;
		const FInfoFailed = 8;
		const MimeTypeFailed = 9;
		const FileError = 10;
		const DirectoryError = 11;
		const AuthenticationError = 12;
		const TypeError = 13;
		const ConnectionFailed = 14;
		const DocumentError = 15;

		const CardUpdateFailed = 1001;
		const CardAssignFailed = 1002;
		const CardRemoveFailed = 1003;
		const CardImportFailed = 1004;

		const CallUpdateFailed = 2001;


		const RouteUpdateFailed = 5001;
		const ScheduleUpdateFailed = 5002;

		const WasteContainerUpdateFailed = 6002;
		const TrashRouteUpdateFailed = 6003;
		const TrashScheduleUpdateFailed = 6004;
		const TrashUpdateError = 6005;

		const VoucherUpdateFailed = 3001;


		const GISEXFailed = 10001;
		const GISEXPushFailed = 10002;

		const ElasticFailed = 11001;

		const ReportFailed = 12001;

		const TelephonyFailed = 13001;

		const WorkflowError = 14001;

		const LDAPError = 15001;
	}
}

 