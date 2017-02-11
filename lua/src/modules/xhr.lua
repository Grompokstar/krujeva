local Xhr = {}


function Xhr.xhrOk(data)

    ngx.header.content_type = 'application/json'

    ngx.say('{"success":true,"data":'..data..',"message":"","code":0}')
end

function Xhr.xhrError(message)

    ngx.header.content_type = 'application/json'

    ngx.say('{"success":false,"data":false,"message":"'.. message..'","code":0}')
end