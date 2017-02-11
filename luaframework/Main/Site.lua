local Site = {}

local pgmoon = require("pgmoon")

local pg = pgmoon.new({
  host = "127.0.0.1",
  port = "5432",
  database = "hairmarket",
  user = "postgres",
  password = "postgres1"
})

Site['request'] = {
	['index']= true,
	['index2']= true,
}

function Site.index()
	ngx.header.content_type = 'application/json'
    ngx.say('site index')
    --return 'index'
end

function Site.index2()



assert(pg:connect())

local res = assert(pg:query("select pg_sleep(10)"))
	ngx.header.content_type = 'application/json'


    ngx.say('site index 21')

    --return 'index2'
end

return Site