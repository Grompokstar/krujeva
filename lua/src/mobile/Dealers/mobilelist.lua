@import "../../modules/app.lua"

local areaid = ngx.var.arg_areaid

areaid = tonumber(areaid)

if areaid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end

local keyDealerArea = 'market.dealerarea.'.. areaid;

local dealersValue = redis:get(keyDealerArea)

if dealersValue == ngx.null then
    ngx.exec("/?areaid=".. areaid)
end

Xhr.xhrOk(dealersValue)