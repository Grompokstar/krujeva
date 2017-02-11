@import "../../modules/app.lua"

local productid = ngx.var.arg_productid

productid = tonumber(productid)

if productid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end

local item = redis:get('market.product.full.'..productid)

if item == ngx.null then
    ngx.exec("/?productid=".. productid)
end

Xhr.xhrOk(item)