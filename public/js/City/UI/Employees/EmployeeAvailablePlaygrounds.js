Module.define(
	"Base.Form",

	function () {
		NS("City.UI.Employees");

		City.UI.Employees.EmployeeAvailablePlaygrounds = Class(Base.Form, {
			template: "City/Employees/EmployeeAvailablePlaygrounds",

			fields: {
				id: 'id',
				userid: 'userid',
				playgroundid: 'playgroundid'
			}

		});
	});