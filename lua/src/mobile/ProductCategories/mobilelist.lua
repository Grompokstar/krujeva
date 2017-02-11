@import "../../modules/app.lua"

local brandid = ngx.var.arg_brandid
local version = ngx.var.arg_version

brandid = tonumber(brandid)
version = tonumber(version)

if brandid == nil then
    Xhr.xhrError('Не все агрументы переданы.')
    return
end

local keyVersion = 'market.brandcategory.version.'..brandid

local currentVersion = redis:get(keyVersion)


if version then

	if currentVersion then

		currentVersion = tonumber(currentVersion)

		if currentVersion == version then
			Xhr.xhrOk('"right version"')
			return
		end

	end

end

local keyCategory = 'market.brandcategory.'.. brandid

local value = redis:get(keyCategory)

if value == ngx.null then
    ngx.exec("/?brandid=".. brandid)
end

Xhr.xhrOk('{"version":'..currentVersion..',"items":'..value..'}')