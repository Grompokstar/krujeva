@import "../../modules/app.lua"

local userid = ngx.var.arg_userid

userid = tonumber(userid)

if userid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end

local key = 'bonus.'.. userid;

local value = redis:get(key)

if value == ngx.null then
    ngx.exec("/?userid=".. userid)
end

Xhr.xhrOk(value)