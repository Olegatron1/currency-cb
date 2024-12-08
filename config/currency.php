<?php

return [
	'cache_ttl' => env('CURRENCY_CACHE_TTL', 86400),
	'default_currency' => 'RUR',
	'soap_wsdl_url' => env('CBR_WSDL_URL', 'https://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL'),

];