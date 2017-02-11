local utils = require "Base.Utils"

local Request = {}


function Request.getController()

	local url = ngx.var.uri

	local paths = utils.splitString(url, '/')

	local countpaths = table.getn(paths)

	local controllers = {}

	if url == '/' or countpaths <= 0 then
		table.insert(controllers, {
			['controller'] = 'Main.Site',
			['action'] = 'index'
		})

	else

		if countpaths == 1 then

			table.insert(controllers, {
				['controller'] = table.concat(utils.splitString(url, '/'), '.'),
				['action'] = 'index'
			})

		else

			local spliturl = utils.splitString(url, '/')

			local action = table.remove(spliturl, table.getn(spliturl))

			table.insert(controllers, {
				['controller'] = table.concat(spliturl, '.'),
				['action'] = action
			})

			table.insert(controllers, {
				['controller'] = table.concat(utils.splitString(url, '/'), '.'),
				['action'] = 'index'
			})

		end

	end

    local resultcontroller = {}

	for i, item in ipairs(controllers) do

    	local status, controller = pcall(function (controller) return require (controller); end, item['controller'])

    	if status and Request.issetAction(controller, item['action']) then

    		table.insert(resultcontroller, controller)

    		resultcontroller = {
    			['controllerobject'] = controller,
    			['action'] = item['action']
    		}

			break
    	end

    end

    return resultcontroller
end

function Request.issetAction(controller, action)

	if controller['request'] == nil then
		return false
	end

	if controller['request'][action] == nil then
		return false
	end

	return true;
end


function Request.callController(controller)

	controller['controllerobject'][controller['action']]()

end


function Request.say(data)

	ngx.header.content_type = 'application/json'

    ngx.say(data)
end

function Request.sayXhrOk(data)

	ngx.header.content_type = 'application/json'

    ngx.say('{"success":true,"data":'..data..',"message":"","code":0}')
end

function Request.sayXhrError(message)

	ngx.header.content_type = 'application/json'

    ngx.say('{"success":false,"data":false,"message":"'.. message..'","code":0}')
end

return Request