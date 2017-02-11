local redislib = require "redis"
local redis = redislib:new()

redis:set_timeout(1000)

local ok, err = redis:connect(Config['redishost'], Config['redisport'])

if not ok then
    ngx.exec('/')
end
