@import "modules/app.lua"

local string = ngx.var.arg_string
local arg2 = ngx.var.arg_arg2

if string == nil then
    ngx.exec("/")
end

local startTime = redis:time()

local ids = Utils.getBitPositions("key1bit", 0, 100, "full.");

local values = redis:mget(unpack(ids))

local res = "["..table.concat(values, ",").."]"

if res == ngx.null then
	ngx.header.content_type = 'application/json'
    ngx.say('work')
else

    ngx.header.content_type = 'application/json'

    ngx.say(Utils.calcExecuteTime(startTime))

    --ngx.say('dd')
end