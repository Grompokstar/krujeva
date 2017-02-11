local request = require "Base.Request"



--local res = assert(pg:query("select current_timestamp as time"))
--request.say(res[1]['time']);
--request.say(utils.var_dump(res));





--Ищем контроллер - для вызова функции
local controller = request.getController();

--Не нашли контроллер => выход
if controller['action'] == nil then
	request.sayXhrError('Not found')
	return
end

--Вызовем
request.callController(controller)

--request.say(controller['action'] == nil);
--request.say('end request');
