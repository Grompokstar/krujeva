@import "../../modules/app.lua"

local orderid = ngx.var.arg_orderid

local json = require "libcjson"

orderid = tonumber(orderid)

if orderid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end


local keyHistory = 'market.order.history.full.'.. orderid;

local item = redis:get(keyHistory);

if item == ngx.null then
   Xhr.xhrError('Не удалось найти')
   return
end

--надо подтянуть товары
local itemJSON = json.decode(item)

if not itemJSON then
	Xhr.xhrError("Не удалось найти..")
	return
end

if not itemJSON['orderproducts'] then
 	Xhr.xhrError("Не удалось найти...")
    return
end

local resultItems = {}

for k,value in pairs(itemJSON['orderproducts']) do

	if value['productid'] then

		local productitem = redis:get("market.product.list."..value['productid'])

		if productitem ~= ngx.null then
			table.insert(resultItems, productitem)
		end

    end

end

local res = '{"item":'..item..',"products":['..table.concat(resultItems, ",")..']}'

Xhr.xhrOk(res)