@import "../../modules/app.lua"

local userid = ngx.var.arg_userid

userid = tonumber(userid)

if userid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end


local keyHistory = 'market.order.history.range.'.. userid;

local listValue = redis:lrange(keyHistory, 0, 200);

local listKeys = {}

for i, id in ipairs(listValue) do

	local item = redis:get("market.order.history.list."..id)

	if item ~= ngx.null then
		table.insert(listKeys, item)
	end
end

local res = '['..table.concat(listKeys, ",")..']'

Xhr.xhrOk(res)