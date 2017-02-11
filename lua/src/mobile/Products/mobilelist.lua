@import "../../modules/app.lua"

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