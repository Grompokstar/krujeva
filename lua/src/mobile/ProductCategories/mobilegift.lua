@import "../../modules/app.lua"

local version = ngx.var.arg_version

brandid = Config['giftbrandid']
version = tonumber(version)

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