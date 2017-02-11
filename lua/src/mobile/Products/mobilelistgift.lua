@import "../../modules/app.lua"

local categoryid = ngx.var.arg_categoryid
local offset = ngx.var.arg_offset
local limit = ngx.var.arg_limit

if not offset then
	offset = 0
end

if not limit then
	limit = 0
end

categoryid = tonumber(categoryid)


if categoryid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end

local ids = Utils.getBitPositions("market.product.category."..categoryid, offset, limit)

local listKeys = {}


for i, id in ipairs(ids) do

	local item = redis:get("market.product.list."..id)

	if item ~= ngx.null then
		table.insert(listKeys, item)
	end

end


if table.getn(listKeys) <= 0 then
    ngx.exec("/?categoryid=".. categoryid.."&offset="..offset.."&limit="..limit)
end


local res = '{"listitems":['..table.concat(listKeys, ",")..']}'

Xhr.xhrOk(res)