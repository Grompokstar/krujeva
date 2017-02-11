@import "../../modules/app.lua"

local value = ngx.var.arg_value

local dealerbrandid = ngx.var.arg_dealerbrandid

local brandid = ngx.var.arg_brandid

local elasticURL = Config['elasticurl']

local json = require "libcjson"

dealerbrandid = tonumber(dealerbrandid)

brandid = tonumber(brandid)

if value == nil or dealerbrandid == nil or brandid == nil then
    Xhr.xhrError('Не все аргументы переданы.')
    return
end


value = Utils.stringUnescape(value)

value = value:gsub("%,", " ")
value = value:gsub("%/", ".")
value = value:gsub("%-", " ")
value = value:gsub("%+", " ")

value = value:gsub("%для ", "")
value = value:gsub("%от ", "")
value = value:gsub("%против ", "")
value = value:gsub("%за ", "")
value = value:gsub("%при ", "")


local splitvalue = Utils.splitString(value.."", " ")

local searchQuery = table.concat(splitvalue, "* AND *")

local term = "brandid:".. brandid .." AND name:(*"..searchQuery.."*)"

local termDescription = "brandid:".. brandid .." AND (name:(*"..searchQuery.."*) OR description:(*"..searchQuery.."*) )"

local res, err = http:request_uri(elasticURL.."/marketnew2/products/_search?pretty=true", {
	method = "GET",
	body = '{"query" : {"query_string" : {"query": "'..term..'","analyze_wildcard": true} }, "size": 30}',
})

if not res then
	Xhr.xhrError("Не удалось найти ")
	return
end

local searchResult = json.decode(res.body)

if not searchResult then
	Xhr.xhrError("Не удалось найти..")
	return
end

if not searchResult['hits'] then
 	Xhr.xhrError("Не удалось найти...")
    return
end

if not searchResult['hits']['hits'] then
 	Xhr.xhrError("Не удалось найти....")
    return
end

local resultItems = {}

for k,value in pairs(searchResult['hits']['hits']) do

	if value['_source'] then

		value['_source']['brandid'] = nil

		value['_source']['productcategoryid'] = nil

		value['_source']['priceobject'] = redis:get("market.dealerprice."..dealerbrandid.."."..value['_source']['id'])

    	table.insert(resultItems, value['_source'])
    end

end

Xhr.xhrOk(json.encode(resultItems))