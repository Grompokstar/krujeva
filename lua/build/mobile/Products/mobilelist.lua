local Config = {}

Config['giftbrandid'] = 8

Config['redishost'] = "127.0.0.1"

Config['redisport'] = 6000

Config['elasticurl'] = "http://127.0.0.1:9200"

local redislib = require "redis"
local redis = redislib:new()

redis:set_timeout(1000)

local ok, err = redis:connect(Config['redishost'], Config['redisport'])

if not ok then
    ngx.exec('/')
end

local Utils = {}

function Utils.getBitPositions(bitKey, offset, limit, keyprefix)

	if not offset then
		offset = 0
	end

	local unlimit = false

	if not limit or limit == ngx.null or limit == 0 then
		unlimit = true
	end

	if not keyprefix then
		keyprefix = ''
	end


	offset = tonumber(offset)

	limit = tonumber(limit)

	local offsetCount = 0

	local offsetLimit = 0

	local bit = require("bit")


	local positions = {}

	local valueKey = redis:get(bitKey)

	if valueKey == ngx.null then return positions end

	local valueLen = string.len(valueKey) - 1

	for i=0, valueLen do

		local iSub = i + 1

		local tmp = string.byte(string.sub(valueKey, iSub, iSub))

		for j=0, 7 do

			if bit.band(tmp, bit.lshift(1, j)) ~= 0 then

				local position = (8 * i) + (7 - j)

				if unlimit then

					table.insert(positions, keyprefix..position)

				else

					if offsetCount >= offset then

						if offsetLimit < limit then

							table.insert(positions, keyprefix..position)

							offsetLimit = offsetLimit + 1
						else
							break
						end

					else
						offsetCount = offsetCount + 1
					end
				end


			end

		end

	end

	return positions
end


function Utils.calcExecuteTime(startTime)
	local currentTime = redis:time()

	local seconds = currentTime[1] - startTime[1]

	local microseconds = (currentTime[2] - startTime[2])

	return seconds..' seconds and '..microseconds..' microseconds'
end

function Utils.stringUnescape(sst)

	local hex_to_char = function(x)
      return string.char(tonumber(x, 16))
    end

	return sst:gsub("%%(%x%x)", hex_to_char)
end

function Utils.splitString(str, pat)

	 local t = {}
	 local fpat = "(.-)" .. pat
	 local last_end = 1
	 local s, e, cap = str:find(fpat, 1)
	 while s do
		if s ~= 1 or cap ~= "" then
	 table.insert(t,cap)
		end
		last_end = e+1
		s, e, cap = str:find(fpat, last_end)
	 end
	 if last_end <= #str then
		cap = str:sub(last_end)
		table.insert(t, cap)
	 end
	 return t
end
local Xhr = {}


function Xhr.xhrOk(data)

    ngx.header.content_type = 'application/json'

    ngx.say('{"success":true,"data":'..data..',"message":"","code":0}')
end

function Xhr.xhrError(message)

    ngx.header.content_type = 'application/json'

    ngx.say('{"success":false,"data":false,"message":"'.. message..'","code":0}')
end
local httplib = require "http"

local http = httplib.new()


local categoryid = ngx.var.arg_categoryid
local dealerbrandid = ngx.var.arg_dealerbrandid
local offset = ngx.var.arg_offset
local limit = ngx.var.arg_limit

if not offset then
	offset = 0
end

if not limit then
	limit = 0
end

categoryid = tonumber(categoryid)

dealerbrandid = tonumber(dealerbrandid)

if categoryid == nil or dealerbrandid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end

local ids = Utils.getBitPositions("market.product.category."..categoryid, offset, limit)

local listKeys = {}

local priceKeys = {}



for i, id in ipairs(ids) do

	local item = redis:get("market.product.list."..id)

	if item ~= ngx.null then
		table.insert(listKeys, item)
	end

	local price = redis:get("market.dealerprice."..dealerbrandid.."."..id)

	if price ~= ngx.null then
		table.insert(priceKeys, price)
	end
end




if table.getn(listKeys) <= 0 then
    ngx.exec("/?categoryid=".. categoryid.."&dealerbrandid="..dealerbrandid.."&offset="..offset.."&limit="..limit)
end


local res = '{"listitems":['..table.concat(listKeys, ",")..'],"prices":['..table.concat(priceKeys, ",")..']}'

Xhr.xhrOk(res)