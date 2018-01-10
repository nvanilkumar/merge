<?php
class phpPayPal {  
	
	// Public VARIABLES
	
	
	// Array of the response variables from PayPal requests
	public $Response;
	
	// Error Variables
	/*	
		$Error = an array of PayPal's response to any paypal errors
		All $_error's = are filled for any error that occurs, including PayPal errors
		Typically, if a method returns false, the $_error's should be filled with error information
	*/
	
	// Array of the error response from PayPal
	//  - [TIMESTAMP] [CORRELATIONID] [ACK] [L_ERRORCODE0] [L_SHORTMESSAGE0] [L_LONGMESSAGE0] [L_SEVERITYCODE0] [VERSION] [BUILD]
	public $Error; 
	
	public $_error = false;
	public $_error_ack;
	public $_error_type;
	public $_error_date;
	public $_error_code;
	public $_error_short_message;
	public $_error_long_message;
	public $_error_corrective_action;
	public $_error_severity_code;
	public $_error_version;
	public $_error_build; 
	public $_error_display_message;
	
	/*
	typical values found in a Request
	Format is  $our_variable_name; // PAYPALS_VARIABLE_NAME
	
	This could probably be organized a bit better
	*/
	
	public $payment_type = 'Sale'; // PAYMENTTYPE
	
	public $email; // EMAIL
	public $salutation; // SALUTATION
	public $first_name; // FIRSTNAME
	public $middle_name; // MIDDLENAME
	public $last_name; // LASTNAME
	public $suffix; // SUFFIX
	public $credit_card_type; // CREDITCARDTYPE --- Visa, MasterCard, Discover, Amex, Switch, Solo
	public $credit_card_number; // ACCT
	public $expire_date;  // EXPDATE - MMYYYY
	public $expire_date_month;
	public $expire_date_year;
	public $cvv2_code; // CVV2
	public $address1; // STREET
	public $address2; // STREET2
	public $city; // CITY
	public $state; // STATE
	public $postal_code; // ZIP
	public $phone_number; // PHONENUM
	
	public $country_code; // COUNTRYCODE
	
	public $currency_code = "USD"; // CURRENCYCODE
	
	public $ip_address; //IPADDRESS
	
	public $amount_total; // AMT
	public $amount_shipping; // SHIPPINGAMT
	public $amount_handling; // HANDLINGAMT
	public $amount_tax; // TAXAMT
	public $amount_sales_tax; // SALESTAX - This is apparently only used with getTransactionDetails, and appears to be the same as amount_tax
	public $amount_items; // ITEMAMT
	public $amount_max; // MAXAMT
	public $amount_fee; // FEEAMT
	public $amount_settle; // SETTLEAMT
	public $amount_refund_net; // NETREFUNDAMT
	public $amount_refund_fee; // FEEREFUNDAMT
	public $amount_refund_total; // GROSSREFUNDAMT
	
	public $shipping_name; // SHIPTONAME
	public $shipping_address1; // SHIPTOSTREET
	public $shipping_address2; // SHIPTOSTREET2
	public $shipping_city; // SHIPTOCITY
	public $shipping_state; // SHIPTOSTATE
	public $shipping_postal_code; // SHIPTOZIP
	public $shipping_country_code; // SHIPTOCOUNTRYCODE
	public $shipping_country_name;
	public $shipping_phone_number; // SHIPTOPHONENUM
	
	public $description; // DESC
	public $custom; // CUSTOM
	public $invoice; // INVNUM
	
	public $note; // NOTE
	
	public $notify_url; // NOTIFYURL
	public $require_confirmed_shipping_address; // REQCONFIRMSHIPPING
	public $no_shipping; // NOSHIPPING
	public $address_override; // ADDROVERRIDE
	public $local_code; // LOCALECODE
	public $page_style; // PAGESTYLE
	public $hdr_image; // HDRIMG
	public $hdr_border_color; //HDRBORDERCOLOR
	public $hdr_back_color; // HDRBACKCOLOR
	public $payflow_color; // PAYFLOWCOLOR
	public $channel_type; // CHANNELTYPE
	public $solution_type; // SOLUTIONTYPE
	
	 // Variables found (usually) to be returned to us
	
	public $ack;
	public $token; // TOKEN
	public $payer_id; // PAYERID
	public $payer_status; // PAYERSTATUS
	public $payer_business; // PAYERBUSINESS
	public $address_owner; // ADDRESSOWNER
	public $address_status; // ADDRESSSTATUS
	public $address_id;
	public $business; // BUSINESS
	
	public $avs_code;
	public $cvv2_match;
	
	public $transaction_id; // TRANSACTIONID
	public $parent_transaction_id; // PARENTTRANSACTIONID
	public $refund_transaction_id; // REFUNDTRANSACTIONID
	public $transaction_type; // TRANSACTIONTYPE
	public $payment_status; // PAYMENTSTATUS
	public $payment_pending_reason; // PENDINGREASON
	public $payment_reason_code; // REASONCODE
	public $order_time; // ORDERTIME
	public $timestamp;
	
	public $exchange_rate; // EXCHANGERATE
	
	public $receipt_id; // RECEIPTID
	
	public $receiver_business; // RECEIVERBUSINESS
	public $receiver_email; // RECEIVEREMAIL
	public $receiver_id; // RECEIVERID
	
	public $subscription_id; // SUBSCRIPTIONID
	public $subscription_date; // SUBSCRIPTIONDATE
	public $effective_date; // EFFECTIVEDATE
	public $retry_time; // RETRYTIME
	public $user_name; // USERNAME
	public $password; // PASSWORD
	public $recurrences; // RECURRENCES
	public $reattempt; // REATTEMPT
	public $recurring; // RECURRING
	public $period; // PERIOD
	public $buyer_id; // BUYERID
	public $closing_date; // CLOSINGDATE
	public $multiitem; // MULTIITEM
	
	public $refund_type; // REFUNDTYPE
	
	// Other
	
	public $build;
	public $version;
	public $correlation_id;
	
	/*
		This is the Items array, contains KEY => VALUE pairs
		for NAME => VALUE of items where NAME = name of item variable
		Names include: name, number, quantity, amount_tax, amount_total
	*/
	public $ItemsArray;
	
	
	/*
		This is an array of country names and their country codes
		Organized in CODE => NAME format
		This is purely for informational purposes, the class itself 
		doesn't use these.
	*/
	
	
	// $countries[COUNTRY_CODE] = COUNTRY_NAME
	public $countries = array ("US"=>"United States","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AI"=>"Anguilla","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan Republic","BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda","BO"=>"Bolivia","BA"=>"Bosnia and Herzegovina","BW"=>"Botswana","BR"=>"Brazil","VG"=>"British Virgin Islands","BN"=>"Brunei","BG"=>"Bulgaria","BF"=>"Burkina Faso","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada","CV"=>"Cape Verde","KY"=>"Cayman Islands","CL"=>"Chile","C2"=>"China","CO"=>"Colombia","CK"=>"Cook Islands","CR"=>"Costa Rica","CI"=>"Cote D'Ivoire","HR"=>"Croatia","CY"=>"Cyprus","CZ"=>"Czech Republic","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","TP"=>"East Timor","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","EE"=>"Estonia","FM"=>"Federated States of Micronesia","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GF"=>"French Guiana","PF"=>"French Polynesia","GA"=>"Gabon Republic","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GI"=>"Gibraltar","GR"=>"Greece","GD"=>"Grenada","GP"=>"Guadeloupe","GU"=>"Guam","GT"=>"Guatemala","GN"=>"Guinea","GY"=>"Guyana","HT"=>"Haiti","HN"=>"Honduras","HK"=>"Hong Kong","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IE"=>"Ireland","IL"=>"Israel","IT"=>"Italy","JM"=>"Jamaica","JP"=>"Japan","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KW"=>"Kuwait","LA"=>"Laos","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LT"=>"Lithuania","LU"=>"Luxembourg","MO"=>"Macau","MK"=>"Macedonia","MG"=>"Madagascar","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MH"=>"Marshall Islands","MQ"=>"Martinique","MU"=>"Mauritius","MX"=>"Mexico","MD"=>"Moldova","MN"=>"Mongolia","MS"=>"Montserrat","MA"=>"Morocco","MZ"=>"Mozambique","NA"=>"Namibia","NP"=>"Nepal","NL"=>"Netherlands","AN"=>"Netherlands Antilles","NZ"=>"New Zealand","NI"=>"Nicaragua","MP"=>"Northern Mariana Islands","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PW"=>"Palau","PS"=>"Palestine","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PL"=>"Poland","PT"=>"Portugal","PR"=>"Puerto Rico","QA"=>"Qatar","RO"=>"Romania","RU"=>"Russia","RW"=>"Rwanda","VC"=>"Saint Vincent and the Grenadines","WS"=>"Samoa","SA"=>"Saudi Arabia","SN"=>"Senegal","CS"=>"Serbia and Montenegro","SC"=>"Seychelles","SG"=>"Singapore","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","ZA"=>"South Africa","KR"=>"South Korea","ES"=>"Spain","LK"=>"Sri Lanka","KN"=>"St. Kitts and Nevis","LC"=>"St. Lucia","SZ"=>"Swaziland","SE"=>"Sweden","CH"=>"Switzerland","TW"=>"Taiwan","TZ"=>"Tanzania","TH"=>"Thailand","TG"=>"Togo","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TC"=>"Turks and Caicos Islands","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VE"=>"Venezuela","VN"=>"Vietnam","VI"=>"Virgin Islands (USA)","YE"=>"Yemen","ZM"=>"Zambia");
	
	
	// States for certain countries, many of these are required formats for PayPal (learned that the hard way)
	//	$states[COUNTRY_CODE][STATE_CODE] = STATE_NAME
	public $states = array(
		'US' => array ("AK"=>"AK","AL"=>"AL","AR"=>"AR","AZ"=>"AZ","CA"=>"CA","CO"=>"CO","CT"=>"CT","DC"=>"DC","DE"=>"DE","FL"=>"FL","GA"=>"GA","HI"=>"HI",
					"IA"=>"IA","ID"=>"ID","IL"=>"IL","IN"=>"IN","KS"=>"KS","KY"=>"KY","LA"=>"LA","MA"=>"MA","MD"=>"MD","ME"=>"ME","MI"=>"MI","MN"=>"MN",
					"MO"=>"MO","MS"=>"MS","MT"=>"MT","NC"=>"NC","ND"=>"ND","NE"=>"NE","NH"=>"NH","NJ"=>"NJ","NM"=>"NM","NV"=>"NV","NY"=>"NY","OH"=>"OH",
					"OK"=>"OK","OR"=>"OR","PA"=>"PA","RI"=>"RI","SC"=>"SC","SD"=>"SD","TN"=>"TN","TX"=>"TX","UT"=>"UT","VA"=>"VA","VT"=>"VT","WA"=>"WA",
					"WI"=>"WI","WV"=>"WV","WY"=>"WY","AA"=>"AA","AE"=>"AE","AP"=>"AP","AS"=>"AS","FM"=>"FM","GU"=>"GU","MH"=>"MH","MP"=>"MP","PR"=>"PR",
					"PW"=>"PW","VI"=>"VI")
				,
		'CA' => array ("AB"=>"Alberta", "BC"=>"British Columbia", "MB"=>"Manitoba", "NB"=>"New Brunswick", "NL"=>"Newfoundland", "NS"=>"Nova Scotia", 
					"NU"=>"Nunavut", "NT"=>"Northwest Territories", "ON"=>"Ontario", "PE"=>"Prince Edward Island", "QC"=>"Quebec", "SK"=>"Saskatchewan", 
					"YT"=>"Yukon")
				,
		'AU' => array ("Australian Capital Territory"=>"Australian Capital Territory","New South Wales"=>"New South Wales","Northern Territory"=>"Northern Territory",
					"Queensland"=>"Queensland","South Australia"=>"South Australia","Tasmania"=>"Tasmania","Victoria"=>"Victoria","Western Australia"=>"Western Australia")
				,
		'GB' => array ("Aberdeen City"=>"Aberdeen City","Aberdeenshire"=>"Aberdeenshire","Angus"=>"Angus","Antrim"=>"Antrim","Argyll and Bute"=>"Argyll and Bute",
					"Armagh"=>"Armagh","Avon"=>"Avon","Bedfordshire"=>"Bedfordshire","Berkshire"=>"Berkshire","Blaenau Gwent"=>"Blaenau Gwent","Borders"=>"Borders",
					"Bridgend"=>"Bridgend","Bristol"=>"Bristol","Buckinghamshire"=>"Buckinghamshire","Caerphilly"=>"Caerphilly","Cambridgeshire"=>"Cambridgeshire",
					"Cardiff"=>"Cardiff","Carmarthenshire"=>"Carmarthenshire","Ceredigion"=>"Ceredigion","Channel Islands"=>"Channel Islands","Cheshire"=>"Cheshire",
					"Clackmannan"=>"Clackmannan","Cleveland"=>"Cleveland","Conwy"=>"Conwy","Cornwall"=>"Cornwall","Cumbria"=>"Cumbria","Denbighshire"=>"Denbighshire",
					"Derbyshire"=>"Derbyshire","Devon"=>"Devon","Dorset"=>"Dorset","Down"=>"Down","Dumfries and Galloway"=>"Dumfries and Galloway","Durham"=>"Durham",
					"East Ayrshire"=>"East Ayrshire","East Dunbartonshire"=>"East Dunbartonshire","East Lothian"=>"East Lothian","East Renfrewshire"=>"East Renfrewshire",
					"East Riding of Yorkshire"=>"East Riding of Yorkshire","East Sussex"=>"East Sussex","Edinburgh City"=>"Edinburgh City","Essex"=>"Essex",
					"Falkirk"=>"Falkirk","Fermanagh"=>"Fermanagh","Fife"=>"Fife","Flintshire"=>"Flintshire","Glasgow"=>"Glasgow","Gloucestershire"=>"Gloucestershire",
					"Greater Manchester"=>"Greater Manchester","Gwynedd"=>"Gwynedd","Hampshire"=>"Hampshire","Herefordshire"=>"Herefordshire",
					"Hertfordshire"=>"Hertfordshire","Highland"=>"Highland","Humberside"=>"Humberside","Inverclyde"=>"Inverclyde","Isle of Anglesey"=>"Isle of Anglesey",
					"Isle of Man"=>"Isle of Man","Isle of Wight"=>"Isle of Wight","Isles of Scilly"=>"Isles of Scilly","Kent"=>"Kent","Lancashire"=>"Lancashire",
					"Leicestershire"=>"Leicestershire","Lincolnshire"=>"Lincolnshire","London"=>"London","Londonderry"=>"Londonderry","Merseyside"=>"Merseyside",
					"Merthyr Tydfil"=>"Merthyr Tydfil","Middlesex"=>"Middlesex","Midlothian"=>"Midlothian","Monmouthshire"=>"Monmouthshire","Moray"=>"Moray",
					"Neath Port Talbot"=>"Neath Port Talbot","Newport"=>"Newport","Norfolk"=>"Norfolk","North Ayrshire"=>"North Ayrshire",
					"North Lanarkshire"=>"North Lanarkshire","North Yorkshire"=>"North Yorkshire","Northamptonshire"=>"Northamptonshire","Northumberland"=>"Northumberland",
					"Nottinghamshire"=>"Nottinghamshire","Orkney"=>"Orkney","Oxfordshire"=>"Oxfordshire","Pembrokeshire"=>"Pembrokeshire",
					"Perthshire and Kinross"=>"Perthshire and Kinross","Powys"=>"Powys","Renfrewshire"=>"Renfrewshire","Rhondda Cynon Taff"=>"Rhondda Cynon Taff",
					"Rutland"=>"Rutland","Shetland"=>"Shetland","Shropshire"=>"Shropshire","Somerset"=>"Somerset","South Ayrshire"=>"South Ayrshire",
					"South Lanarkshire"=>"South Lanarkshire","South Yorkshire"=>"South Yorkshire","Staffordshire"=>"Staffordshire","Stirling"=>"Stirling",
					"Suffolk"=>"Suffolk","Surrey"=>"Surrey","Swansea"=>"Swansea","The Vale of Glamorgan"=>"The Vale of Glamorgan","Tofaen"=>"Tofaen",
					"Tyne and Wear"=>"Tyne and Wear","Tyrone"=>"Tyrone","Warwickshire"=>"Warwickshire","West Dunbartonshire"=>"West Dunbartonshire",
					"West Lothian"=>"West Lothian","West Midlands"=>"West Midlands","West Sussex"=>"West Sussex","West Yorkshire"=>"West Yorkshire",
					"Western Isles"=>"Western Isles","Wiltshire"=>"Wiltshire","Worcestershire"=>"Worcestershire","Wrexham"=>"Wrexham")
				,
		'ES' => array ("Alava" => "Alava", "Albacete" => "Albacete", "Alicante" => "Alicante", "Almeria" => "Almeria", "Asturias" => "Asturias", 
					"Avila" => "Avila", "Badajoz" => "Badajoz", "Barcelona" => "Barcelona", "Burgos" => "Burgos", "Caceres" => "Caceres", 
					"Cadiz" => "Cadiz", "Cantabria" => "Cantabria", "Castellon" => "Castellon", "Ceuta" => "Ceuta", "Ciudad Real" => "Ciudad Real", 
					"Cordoba" => "Cordoba", "Cuenca" => "Cuenca", "Guadalajara" => "Guadalajara", "Gerona" => "Gerona", "Granada" => "Granada", 
					"Guipuzcoa" => "Guipuzcoa", "Huelva" => "Huelva", "Huesca" => "Huesca", "Islas Baleares" => "Islas Baleares", "Jaen" => "Jaen", 
					"La Coruna" => "La Coruna", "Las Palmas" => "Las Palmas", "La Rioja" => "La Rioja", "Leon" => "Leon", "Lerida" => "Lerida", 
					"Lugo" => "Lugo", "Madrid" => "Madrid", "Malaga" => "Malaga", "Melilla" => "Melilla", "Murcia" => "Murcia", "Navarra" => "Navarra", 
					"Orense" => "Orense", "Palencia" => "Palencia", "Pontevedra" => "Pontevedra", "Salamanca" => "Salamanca", 
					"Santa Cruz de Tenerife" => "Santa Cruz de Tenerife", "Segovia" => "Segovia", "Sevilla" => "Sevilla", "Soria" => "Soria", 
					"Tarragona" => "Tarragona", "Teruel" => "Teruel", "Toledo" => "Toledo", "Valencia" => "Valencia", "Valladolid" => "Valladolid", 
					"Vizcaya" => "Vizcaya", "Zamora" => "Zamora", "Zaragoza" => "Zaragoza")
				);
	

	
	// Internal Use
	
	
	// AVS Response Code Values and Meanings
	//	$AvsResponseCodesArray[CODE][MESSAGE]
	//	$AvsResponseCodesArray[CODE][DETAILS]
	public $AvsResponseCodesArray = array (
			'A' => array('message' => 'Address', 'details' => 'Address Only (no ZIP)'),
			'B' => array('message' => 'International "A"', 'details' => 'Address Only (no ZIP)'),
			'C' => array('message' => 'International "N"', 'details' => 'None - The transaction is declined.'),
			'D' => array('message' => 'International "X"', 'details' => 'Address and Postal Code'),
			'E' => array('message' => 'Not Allowed for MOTO (Internet/Phone) transactions', 'details' => 'Not applicable - The transaction is declined.'),
			'F' => array('message' => 'UK-Specific "X"', 'details' => 'Address and Postal Code'),
			'G' => array('message' => 'Global Unavailable', 'details' => 'Not applicable'),
			'I' => array('message' => 'International Unavailable', 'details' => 'Not applicable'),
			'N' => array('message' => 'No', 'details' => 'None - The transaction is declined.'),
			'P' => array('message' => 'Postal (International "Z")', 'details' => 'Postal Code only (no Address)'),
			'R' => array('message' => 'Retry', 'details' => 'Not Applicable'),
			'S' => array('message' => 'Service Not Supported', 'details' => 'Not Applicable'),
			'U' => array('message' => 'Unavailable', 'details' => 'Not Applicable'),
			'W' => array('message' => 'Whole ZIP', 'details' => 'Nine-digit ZIP code (no Address)'),
			'X' => array('message' => 'Exact Match', 'details' => 'Address and nine-digit ZIP code'),
			'Y' => array('message' => 'Yes', 'details' => 'Address and five-digit ZIP'),
			'Z' => array('message' => 'ZIP', 'details' => 'Five-digit ZIP code (no Address)'),
			'' => array('message' => 'Error', 'details' => 'Not Applicable')
		);
			
	// CVV Rsponse Code Values and Meanings
	//	$CvvResponseCodesArray[CODE][MESSAGE]
	//	$CvvResponseCodesArray[CODE][DETAILS]
	public $CvvResponseCodesArray = array (
			'M' => array('message' => 'Match', 'details' => 'CVV2'), 
			'N' => array('message' => 'No Match', 'details' => 'None'), 
			'P' => array('message' => 'Not Processed', 'details' => 'Not Applicable'), 
			'S' => array('message' => 'Service not supported', 'details' => 'Not Applicable'), 
			'U' => array('message' => 'Service not available', 'details' => 'Not Applicable'), 
			'X' => array('message' => 'No response', 'details' => 'Not Applicable')
		);
	
	// CVV Rsponse Code Values and Meanings for Switch and Solo cards
	// TODO: ??
			
	
	
	
	public $RequestFieldsArray = array(
		'DoDirectPayment' => array(
				'payment_type' => array('name' => 'PAYMENTACTION', 'required' => 'yes'),
				'ip_address' => array('name' => 'IPADDRESS', 'required' => 'yes'),
				'amount_total' => array('name' => 'AMT', 'required' => 'yes'), 
				'credit_card_type' => array('name' => 'CREDITCARDTYPE', 'required' => 'yes'), 
				'credit_card_number' => array('name' => 'ACCT', 'required' => 'yes'), 
				'expire_date' => array('name' => 'EXPDATE', 'required' => 'yes'), 
				'first_name' => array('name' => 'FIRSTNAME', 'required' => 'yes'), 
				'last_name' => array('name' => 'LASTNAME', 'required' => 'yes'), 
				'address1' => array('name' => 'STREET', 'required' => 'no'), 
				'address2' => array('name' => 'STREET2', 'required' => 'no'), 
				'city' => array('name' => 'CITY', 'required' => 'no'), 
				'state' => array('name' => 'STATE', 'required' => 'no'), 
				'country_code' => array('name' => 'COUNTRYCODE', 'required' => 'no'), 
				'postal_code' => array('name' => 'ZIP', 'required' => 'no'), 
				'notify_url' => array('name' => 'NOTIFYURL', 'required' => 'no'), 
				'currency_code' => array('name' => 'CURRENCYCODE', 'required' => 'no'), 
				'amount_items' => array('name' => 'ITEMAMT', 'required' => 'no'), 
				'amount_shipping' => array('name' => 'SHIPPINGAMT', 'required' => 'no'), 
				'amount_handling' => array('name' => 'HANDLINGAMT', 'required' => 'no'), 
				'amount_tax' => array('name' => 'TAXAMT', 'required' => 'no'), 
				'description' => array('name' => 'DESC', 'required' => 'no'), 
				'custom' => array('name' => 'CUSTOM', 'required' => 'no'), 
				'invoice' => array('name' => 'INVNUM', 'required' => 'no'), 
				'cvv2_code' => array('name' => 'CVV2', 'required' => 'yes'), 
				'email' => array('name' => 'EMAIL', 'required' => 'no'), 
				'phone_number' => array('name' => 'PHONENUM', 'required' => 'no'), 
				'shipping_name' => array('name' => 'SHIPTONAME', 'required' => 'no'), 
				'shipping_address1' => array('name' => 'SHIPTOSTREET', 'required' => 'no'), 
				'shipping_address2' => array('name' => 'SHIPTOSTREET2', 'required' => 'no'), 
				'shipping_city' => array('name' => 'SHIPTOCITY', 'required' => 'no'), 
				'shipping_state' => array('name' => 'SHIPTOSTATE', 'required' => 'no'), 
				'shipping_postal_code' => array('name' => 'SHIPTOZIP', 'required' => 'no'), 
				'shipping_country_code' => array('name' => 'SHIPTOCOUNTRYCODE', 'required' => 'no'), 
				'shipping_phone_number' => array('name' => 'SHIPTOPHONENUM', 'required' => 'no')
				)
		,		
		'SetExpressCheckout' => array(
				'RETURN_URL' => array('name' => 'RETURNURL', 'required' => 'yes'),
				'CANCEL_URL' => array('name' => 'CANCELURL', 'required' => 'yes'),
				'amount_total' => array('name' => 'AMT', 'required' => 'yes'), 
				'currency_code' => array('name' => 'CURRENCYCODE', 'required' => 'no'), 
				'amount_max' => array('name' => 'MAXAMT', 'required' => 'no'), 
				'payment_type' => array('name' => 'PAYMENTACTION', 'required' => 'no'), 
				'email' => array('name' => 'EMAIL', 'required' => 'no'), 
				'description' => array('name' => 'DESC', 'required' => 'no'), 
				'custom' => array('name' => 'CUSTOM', 'required' => 'no'), 
				'invoice' => array('name' => 'INVNUM', 'required' => 'no'), 
				'phone_number' => array('name' => 'PHONENUM', 'required' => 'no'), 
				'shipping_name' => array('name' => 'SHIPTONAME', 'required' => 'no'), 
				'shipping_address1' => array('name' => 'SHIPTOSTREET', 'required' => 'no'), 
				'shipping_address2' => array('name' => 'SHIPTOSTREET2', 'required' => 'no'), 
				'shipping_city' => array('name' => 'SHIPTOCITY', 'required' => 'no'), 
				'shipping_state' => array('name' => 'SHIPTOSTATE', 'required' => 'no'), 
				'shipping_postal_code' => array('name' => 'SHIPTOZIP', 'required' => 'no'), 
				'shipping_country_code' => array('name' => 'SHIPTOCOUNTRYCODE', 'required' => 'no'), 
				'shipping_phone_number' => array('name' => 'SHIPTOPHONENUM', 'required' => 'no'), 
				'require_confirmed_shipping_address' => array('name' => 'REQCONFIRMSHIPPING', 'required' => 'no'),
				'no_shipping' => array('name' => 'NOSHIPPING', 'required' => 'no'), 
				'address_override' => array('name' => 'ADDROVERRIDE', 'required' => 'no'), 
				'token' => array('name' => 'TOKEN', 'required' => 'no'), 
				'locale_code' => array('name' => 'LOCALECODE', 'required' => 'no'), 
				'page_style' => array('name' => 'PAGESTYLE', 'required' => 'no'), 
				'hdr_img' => array('name' => 'HDRIMG', 'required' => 'no'), 
				'hdr_border_color' => array('name' => 'HDRBORDERCOLOR', 'required' => 'no'), 
				'hdr_background_color' => array('name' => 'HDRBACKCOLOR', 'required' => 'no'), 
				'payflow_color' => array('name' => 'PAYFLOWCOLOR', 'required' => 'no'), 
				'channel_type' => array('name' => 'CHANNELTYPE', 'required' => 'no'), 
				'solution_type' => array('name' => 'SOLUTIONTYPE', 'required' => 'no') 
				)
		,		
		'GetExpressCheckoutDetails' => array(
				'token' => array('name' => 'TOKEN', 'required' => 'yes')
				)
		,		
		'DoExpressCheckoutPayment' => array(
				'token' => array('name' => 'TOKEN', 'required' => 'yes'),
				'payment_type' => array('name' => 'PAYMENTACTION', 'required' => 'yes'), 
				'payer_id' => array('name' => 'PAYERID', 'required' => 'yes'),
				'amount_total' => array('name' => 'AMT', 'required' => 'yes'), 
				'description' => array('name' => 'DESC', 'required' => 'no'), 
				'custom' => array('name' => 'CUSTOM', 'required' => 'no'), 
				'invoice' => array('name' => 'INVNUM', 'required' => 'no'), 
				'notify_url' => array('name' => 'NOTIFYURL', 'required' => 'no'), 
				'amount_items' => array('name' => 'ITEMAMT', 'required' => 'no'), 
				'amount_shipping' => array('name' => 'SHIPPINGAMT', 'required' => 'no'), 
				'amount_handling' => array('name' => 'HANDLINGAMT', 'required' => 'no'), 
				'amount_tax' => array('name' => 'TAXAMT', 'required' => 'no'), 
				'currency_code' => array('name' => 'CURRENCYCODE', 'required' => 'no'), 
				'shipping_name' => array('name' => 'SHIPTONAME', 'required' => 'no'), 
				'shipping_address1' => array('name' => 'SHIPTOSTREET', 'required' => 'no'), 
				'shipping_address2' => array('name' => 'SHIPTOSTREET2', 'required' => 'no'), 
				'shipping_city' => array('name' => 'SHIPTOCITY', 'required' => 'no'), 
				'shipping_state' => array('name' => 'SHIPTOSTATE', 'required' => 'no'), 
				'shipping_postal_code' => array('name' => 'SHIPTOZIP', 'required' => 'no'), 
				'shipping_country_code' => array('name' => 'SHIPTOCOUNTRYCODE', 'required' => 'no'), 
				'shipping_phone_number' => array('name' => 'SHIPTOPHONENUM', 'required' => 'no')
				)
		,		
		'GetTransactionDetails' => array(
				'transaction_id' => array('name' => 'TRANSACTIONID', 'required' => 'yes')
				)
		,		
		'RefundTransaction' => array(
				'transaction_id' => array('name' => 'TRANSACTIONID', 'required' => 'yes'), 
				'refund_type' => array('name' => 'REFUNDTYPE', 'required' => 'yes'), 
				'amount_total' => array('name' => 'AMT', 'required' => 'no'), 
				'note' => array('name' => 'NOTE', 'required' => 'no'),
				)
		);
	
	
	public $ResponseFieldsArray = array(
		'DoDirectPayment' => array(
				'timestamp' => 'TIMESTAMP',
				'correlation_id' => 'CORRELATIONID',
				'ack' => 'ACK',
				'version' => 'VERSION',
				'build' => 'BUILD',
				'avs_code' => 'AVSCODE',
				'cvv2_match' => 'CVV2MATCH',
				'transaction_id' => 'TRANSACTIONID',
				'amount_total' => 'AMT',
				'currency_code' => 'CURRENCYCODE'
				)
		,
		'SetExpressCheckout' => array(
				'timestamp' => 'TIMESTAMP',
				'correlation_id' => 'CORRELATIONID',
				'ack' => 'ACK',
				'version' => 'VERSION',
				'build' => 'BUILD',
				'token' => 'TOKEN'
				)
		,
		'DoExpressCheckoutPayment' => array(
				'timestamp' => 'TIMESTAMP',
				'correlation_id' => 'CORRELATIONID',
				'ack' => 'ACK',
				'version' => 'VERSION',
				'build' => 'BUILD',
				'token' => 'TOKEN',
				'transaction_id' => 'TRANSACTIONID',
				'transaction_type' => 'TRANSACTIONTYPE',
				'payment_type' => 'PAYMENTTYPE',
				'order_time' => 'ORDERTIME',
				'amount_total' => 'AMT',
				'currency_code' => 'CURRENCYCODE',
				'amount_fee' => 'FEEAMT',
				'amount_settle' => 'SETTLEAMT',
				'amount_tax' => 'TAXAMT',
				'exchange_rate' => 'EXCHANGERATE',
				'payment_status' => 'PAYMENTSTATUS',
				'payment_pending_reason' => 'PENDINGREASON',
				'payment_reason_code' => 'REASONCODE'
				)
		,
		'GetExpressCheckoutDetails' => array(
				'timestamp' => 'TIMESTAMP',
				'correlation_id' => 'CORRELATIONID',
				'ack' => 'ACK',
				'version' => 'VERSION',
				'build' => 'BUILD',
				'token' => 'TOKEN',
				'email' => 'EMAIL',
				'payer_id' => 'PAYERID',
				'payer_status' => 'PAYERSTATUS',
				'salutation' => 'SALUTATION',
				'first_name' => 'FIRSTNAME',
				'middle_name' => 'MIDDLENAME',
				'last_name' => 'LASTNAME',
				'suffix' => 'SUFFIX',
				'country_code' => 'COUNTRYCODE',
				'business' => 'BUSINESS',
				'shipping_name' => 'SHIPTONAME',
				'shipping_address1' => 'SHIPTOSTREET',
				'shipping_address2' => 'SHIPTOSTREET2',
				'shipping_city' => 'SHIPTOCITY',
				'shipping_state' => 'SHIPTOSTATE',
				'shipping_country_code' => 'SHIPTOCOUNTRYCODE',
				'shipping_country_name' => 'SHIPTOCOUNTRYNAME',
				'shipping_postal_code' => 'SHIPTOZIP',
				'address_id' => 'ADDRESSID', // Is this a returned variable? Some docs say yes, some no
				'address_status' => 'ADDRESSSTATUS',
				'description' => 'DESC',
				'custom' => 'CUSTOM',
				'phone_number' => 'PHONENUM'
				)
		,
		'GetTransactionDetails' => array(
				'timestamp' => 'TIMESTAMP',
				'correlation_id' => 'CORRELATIONID',
				'ack' => 'ACK',
				'version' => 'VERSION',
				'build' => 'BUILD',
				'receiver_business' => 'RECEIVERBUSINESS',
				'receiver_email' => 'RECEIVEREMAIL',
				'receiver_id' => 'RECEIVERID',
				'email' => 'EMAIL',
				'payer_id' => 'PAYERID',
				'payer_status' => 'PAYERSTATUS',
				'salutation' => 'SALUTATION',
				'first_name' => 'FIRSTNAME',
				'last_name' => 'LASTNAME',
				'middle_name' => 'MIDDLENAME',
				'suffix' => 'SUFFIX',
				'payer_business' => 'PAYERBUSINESS',
				'country_code' => 'COUNTRYCODE',
				'business' => 'BUSINESS',
				'shipping_name' => 'SHIPTONAME',
				'shipping_address1' => 'SHIPTOSTREET',
				'shipping_address2' => 'SHIPTOSTREET2',
				'shipping_city' => 'SHIPTOCITY',
				'shipping_state' => 'SHIPTOSTATE',
				'shipping_country_code' => 'SHIPTOCOUNTRYCODE',
				'shipping_country_name' => 'SHIPTOCOUNTRYNAME',
				'shipping_postal_code' => 'SHIPTOZIP',
				'address_id' => 'ADDRESSID', // Is this a returned variable? Some docs say yes, some no
				'address_status' => 'ADDRESSSTATUS',
				'address_owner' => 'ADDRESSOWNER',
				'parent_transaction_id' => 'PARENTTRANSACTIONID',
				'transaction_id' => 'TRANSACTIONID',
				'receipt_id' => 'RECEIPTID',
				'transaction_type' => 'TRANSACTIONTYPE',
				'payment_type' => 'PAYMENTTYPE',
				'order_time' => 'ORDERTIME',
				'amount_total' => 'AMT',
				'currency_code' => 'CURRENCYCODE',
				'amount_fee' => 'FEEAMT',
				'amount_settle' => 'SETTLEAMT',
				'amount_tax' => 'TAXAMT',
				'exchange_rate' => 'EXCHANGERATE',
				'payment_status' => 'PAYMENTSTATUS',
				'payment_pending_reason' => 'PENDINGREASON',
				'payment_reason_code' => 'REASONCODE',
				'amount_sales_tax' => 'SALESTAX',
				'invoice' => 'INVNUM',
				'note' => 'NOTE',
				'custom' => 'CUSTOM',
				'subscription_id' => 'SUBSCRIPTIONID',
				'subscription_date' => 'SUBSCRIPTIONDATE',
				'effective_date' => 'EFFECTIVEDATE',
				'retry_time' => 'RETRYTIME',
				'user_name' => 'USERNAME',
				'recurrences' => 'RECURRENCES',
				'reattempt' => 'REATTEMPT',
				'recurring' => 'RECURRING',
				'period' => 'PERIOD',
				'buyer_id' => 'BUYERID',
				'closing_date' => 'CLOSINGDATE',
				'multiitem' => 'MULTIITEM'
				)
		,
		'RefundTransaction' => array(
				'refund_transaction_id' => 'REFUNDTRANSACTIONID',
				'amount_refund_net' => 'NETFUNDAMT',
				'amount_refund_fee' => 'FEEREFUNDAMT',
				'amount_refund_total' => 'GROSSREFUNDAMT'
				)
		);
	
	
	
				
	// Private VARIABLES
	
	
	/****************************************************
	PayPal includes the following API Signature for making API
	calls to the PayPal sandbox:
	
	API Username 	sdk-three_api1.sdk.com
	API Password 	QFZCWN5HZM8VBG7Q
	API Signature 	A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU
	****************************************************/
	
	/**
	# API user: The user that is identified as making the call. you can
	# also use your own API username that you created on PayPal’s sandbox
	# or the PayPal live site
	*/
	
	//	LIVE
	// private $API_USERNAME = '';
	//	SANDBOX
	//private $API_USERNAME = 'sdk-three_api1.sdk.com';
//	private $API_USERNAME = 'ryk007_1246944396_biz_api1.gmail.com';	


	public $API_USERNAME ;	
	/**
	# API_password: The password associated with the API user
	# If you are using your own API username, enter the API password that
	# was generated by PayPal below
	# IMPORTANT - HAVING YOUR API PASSWORD INCLUDED IN THE MANNER IS NOT
	# SECURE, AND ITS ONLY BEING SHOWN THIS WAY FOR TESTING PURPOSES
	*/
	
	//	LIVE
	// private $API_PASSWORD = '';
	//	SANDBOX
	//private $API_PASSWORD = 'QFZCWN5HZM8VBG7Q';
	//private $API_PASSWORD = '1246944406';
	public $API_PASSWORD ;
	
	/**
	# API_Signature:The Signature associated with the API user. which is generated by paypal.
	*/
	
	//	LIVE
	// private $API_SIGNATURE = '';
	//	SANDBOX
//	private $API_SIGNATURE = 'A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU';
//	private $API_SIGNATURE = 'AFcWxV21C7fd0v3bYYYRCpSSRl31ATPjSheU7fJBWt2BZYMytzbvanDk';
	public $API_SIGNATURE ;
	
	/**
	# Endpoint: this is the server URL which you have to connect for submitting your API request.
	*/
	
	//	LIVE
	// private $API_ENDPOINT = 'https://api-3t.paypal.com/nvp';
	//	SANDBOX 
	//private $API_ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
	public $API_ENDPOINT ;
	/**
	USE_PROXY: Set this variable to TRUE to route all the API requests through proxy.
	like define('USE_PROXY',TRUE);
	
	We don't appear to be using this in phpPayPal
	*/
	private $USE_PROXY = FALSE;
	/**
	PROXY_HOST: Set the host name or the IP address of proxy server.
	PROXY_PORT: Set proxy port.
	
	PROXY_HOST and PROXY_PORT will be read only if USE_PROXY is set to TRUE
	*/
	private $PROXY_HOST = '127.0.0.1';
	private $PROXY_PORT = '808';
	
	/* Define the PayPal URL. This is the URL that the buyer is
	   first sent to to authorize payment with their paypal account
	   change the URL depending if you are testing on the sandbox
	   or going to the live PayPal site
	   For the sandbox, the URL is
	   https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
	   For the live site, the URL is
	   https://www.paypal.com/webscr&cmd=_express-checkout&token=
	   */
	
	//	LIVE
	 private $PAYPAL_URL = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
	//	SANDBOX
	//private $PAYPAL_URL = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
	
	/* Define the Reutrn and Cancel URLs. 
	   The returnURL is the location where buyers return when a
	   payment has been succesfully authorized.
	   The cancelURL is the location buyers are sent to when they hit the
	   cancel button during authorization of payment during the PayPal flow
	   */
	private $RETURN_URL = '';
	private $CANCEL_URL = '';
	
	/**
	# Version: this is the API version in the request.
	# It is a mandatory parameter for each API request.
	# The only supported value at this time is 2.3
	*/
	
	public $VERSION = '3.0';
	
	
	
	
	
	
	
	// CONSTRUCT
	function __construct()
		{
		// You could set variable defaults here if you like
		}	
	
	
	
	
	
	
	public function DoDirectPayment()
		{
		// urlencode the needed variables
		$this->urlencodeVariables();
		
		/* Construct the request string that will be sent to PayPal.
		   The variable $nvpstr contains all the variables and is a
		   name value pair string with & as a delimiter */
		$nvpstr = $this->generateNVPString('DoDirectPayment');
		
		/* Construct and add any items found in this instance */
		if(!empty($this->ItemsArray))
			{
			// Counter for the total of all the items put together
			$total_items_amount = 0;
			// Go through the items array
			foreach($this->ItemsArray as $key => $value)
				{
				// Get the array of the current item from the main array
				$current_item = $this->ItemsArray[$key];
				// Add it to the request string
				$nvpstr .= "&L_NAME".$key."=".$current_item['name'].
							"&L_NUMBER".$key."=".$current_item['number'].
							"&L_QTY".$key."=".$current_item['quantity'].
							"&L_TAXAMT".$key."=".$current_item['amount_tax'].
							"&L_AMT".$key."=".$current_item['amount'];
				// Add this item's amount to the total current count
				$total_items_amount += ($current_item['amount'] * $current_item['quantity']);
				}
			// Set the amount_items for this instance and ITEMAMT added to the request string
			$this->amount_items = $total_items_amount;
			$nvpstr .= "&ITEMAMT=".urlencode($total_items_amount);
			}
		
		// decode the variables incase we still require access to them in our program
		$this->urldecodeVariables();
		
		/* Make the API call to PayPal, using API signature.
		   The API response is stored in an associative array called $this->Response */
		$this->Response = $this->hash_call("DoDirectPayment", $nvpstr);
		
		// TODO: Add error handling for the hash_call
		
		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		
		/*
			*************
			if NO SUCCESS
			*************
			*/
		if(strtoupper($this->Response["ACK"]) != "SUCCESS" AND strtoupper($this->Response["ACK"]) != "SUCCESSWITHWARNING")
			{
			$this->Error['TIMESTAMP']		= @$this->Response['TIMESTAMP'];
			$this->Error['CORRELATIONID']	= @$this->Response['CORRELATIONID'];
			$this->Error['ACK']				= $this->Response['ACK'];
			$this->Error['ERRORCODE']		= $this->Response['L_ERRORCODE0'];
			$this->Error['SHORTMESSAGE']	= $this->Response['L_SHORTMESSAGE0'];
			$this->Error['LONGMESSAGE']		= $this->Response['L_LONGMESSAGE0'];
			$this->Error['SEVERITYCODE']	= $this->Response['L_SEVERITYCODE0'];
			$this->Error['VERSION']			= @$this->Response['VERSION'];
			$this->Error['BUILD']			= @$this->Response['BUILD'];
			
			// TODO: Error codes for AVSCODE and CVV@MATCH
			
			$this->_error				= true;
			$this->_error_ack			= $this->Response['ACK'];
			$this->ack					= 'Failure';
			$this->_error_type			= 'paypal';
			$this->_error_date			= $this->Response['TIMESTAMP'];
			$this->_error_code			= $this->Response['L_ERRORCODE0'];
			$this->_error_short_message	= $this->Response['L_SHORTMESSAGE0'];
			$this->_error_long_message	= $this->Response['L_LONGMESSAGE0'];
			$this->_error_severity_code	= $this->Response['L_SEVERITYCODE0'];
			$this->_error_version		= @$this->Response['VERSION'];
			$this->_error_build			= @$this->Response['BUILD']; 
			
			return false;
			}
			/*
			*************
			if SUCCESS
			*************
			*/
		elseif(strtoupper($this->Response["ACK"]) == 'SUCCESS' OR strtoupper($this->Response["ACK"]) == 'SUCCESSWITHWARNING')
			{
			/*
			Take the response variables and put them into the local class variables
			*/
			foreach($this->ResponseFieldsArray['DoDirectPayment'] as $key => $value)
				$this->$key = $this->Response[$value];
			
			return true;
			}
		}
	
	
	
	
	
	function SetExpressCheckout()
		{
		// TODO: Add error handling prior to trying to make PayPal calls. ie: missing amount_total or RETURN_URL
		
		// urlencode the needed variables
		$this->urlencodeVariables();
		
		/* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
		$nvpstr = $this->generateNVPString('SetExpressCheckout');
				
		// decode the variables incase we still require access to them in our program
		$this->urldecodeVariables();
		
		/* Make the call to PayPal to set the Express Checkout token
			If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors
			*/
		$this->Response = $this->hash_call("SetExpressCheckout", $nvpstr);
		
		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */		
		/*
			*************
			if NO SUCCESS
			*************
			*/
		if(strtoupper($this->Response["ACK"]) != "SUCCESS")
			{
			$this->Error['TIMESTAMP']		= @$this->Response['TIMESTAMP'];
			$this->Error['CORRELATIONID']	= @$this->Response['CORRELATIONID'];
			$this->Error['ACK']				= $this->Response['ACK'];
			$this->Error['ERRORCODE']		= $this->Response['L_ERRORCODE0'];
			$this->Error['SHORTMESSAGE']	= $this->Response['L_SHORTMESSAGE0'];
			$this->Error['LONGMESSAGE']		= $this->Response['L_LONGMESSAGE0'];
			$this->Error['SEVERITYCODE']	= $this->Response['L_SEVERITYCODE0'];
			$this->Error['VERSION']			= @$this->Response['VERSION'];
			$this->Error['BUILD']			= @$this->Response['BUILD'];
			
			$this->_error				= true;
			$this->_error_ack			= $this->Response['ACK'];
			$this->ack					= 'Failure';
			$this->_error_type			= 'paypal';
			$this->_error_date			= $this->Response['TIMESTAMP'];
			$this->_error_code			= $this->Response['L_ERRORCODE0'];
			$this->_error_short_message	= $this->Response['L_SHORTMESSAGE0'];
			$this->_error_long_message	= $this->Response['L_LONGMESSAGE0'];
			$this->_error_severity_code	= $this->Response['L_SEVERITYCODE0'];
			$this->_error_version		= @$this->Response['VERSION'];
			$this->_error_build			= @$this->Response['BUILD']; 
			
			return false;
			/*
			$_SESSION['reshash']=$this->Response;
			$location = "APIError.php";
			header("Location: $location");
			*/
			}
		/*
			*************
			if SUCCESS
			*************
			*/
		elseif(strtoupper($this->Response["ACK"]) == 'SUCCESS')
			{
			/*
			Take the response variables and put them into the local class variables
			*/
			foreach($this->ResponseFieldsArray['SetExpressCheckout'] as $key => $value)
				$this->$key = $this->Response[$value];
			
			return true;
			}
		}
	
	function SetExpressCheckoutSuccessfulRedirect()
		{
		// Redirect to paypal.com here
		$token = urlencode($this->Response["TOKEN"]);
		$paypal_url = $this->PAYPAL_URL.$token;
		header("Location: ".$paypal_url);
		}
	
	
	
	
	function GetExpressCheckoutDetails()
		{
		// TODO: Add error handling prior to PayPal calls. ie: missing TOKEN
		
		/* At this point, the buyer has completed in authorizing payment
			at PayPal.  The script will now call PayPal with the details
			of the authorization, incuding any shipping information of the
			buyer.  Remember, the authorization is not a completed transaction
			at this state - the buyer still needs an additional step to finalize
			the transaction
			*/

		 /* Build a second API request to PayPal, using the token as the
			ID to get the details on the payment authorization
			*/
		/* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
		$nvpstr = $this->generateNVPString('GetExpressCheckoutDetails');

		 /* Make the API call and store the results in an array.  If the
			call was a success, show the authorization details, and provide
			an action to complete the payment.  If failed, show the error
			*/
		$this->Response = $this->hash_call("GetExpressCheckoutDetails", $nvpstr);
		
		/*
			*************
			if NO SUCCESS
			*************
			*/
		if(strtoupper($this->Response["ACK"]) != "SUCCESS")
			{
			$this->Error['TIMESTAMP']		= @$this->Response['TIMESTAMP'];
			$this->Error['CORRELATIONID']	= @$this->Response['CORRELATIONID'];
			$this->Error['ACK']				= $this->Response['ACK'];
			$this->Error['ERRORCODE']		= $this->Response['L_ERRORCODE0'];
			$this->Error['SHORTMESSAGE']	= $this->Response['L_SHORTMESSAGE0'];
			$this->Error['LONGMESSAGE']		= $this->Response['L_LONGMESSAGE0'];
			$this->Error['SEVERITYCODE']	= $this->Response['L_SEVERITYCODE0'];
			$this->Error['VERSION']			= @$this->Response['VERSION'];
			$this->Error['BUILD']			= @$this->Response['BUILD'];
			
			$this->_error				= true;
			$this->_error_ack			= $this->Response['ACK'];
			$this->ack					= 'Failure';
			$this->_error_type			= 'paypal';
			$this->_error_date			= $this->Response['TIMESTAMP'];
			$this->_error_code			= $this->Response['L_ERRORCODE0'];
			$this->_error_short_message	= $this->Response['L_SHORTMESSAGE0'];
			$this->_error_long_message	= $this->Response['L_LONGMESSAGE0'];
			$this->_error_severity_code	= $this->Response['L_SEVERITYCODE0'];

			$this->_error_version		= @$this->Response['VERSION'];
			$this->_error_build			= @$this->Response['BUILD']; 
			
			return false;
			/*
			$_SESSION['reshash']=$this->Response;
			$location = "APIError.php";
			header("Location: $location");
			*/
			}
		/*
			***********
			if SUCCESS
			***********
			*/
		elseif(strtoupper($this->Response["ACK"]) == 'SUCCESS')
			{
			/*
			Take the response variables and put them into the local class variables
			*/
			foreach($this->ResponseFieldsArray['GetExpressCheckoutDetails'] as $key => $value)
				$this->$key = $this->Response[$value];
			
			return true;
			}
		
		}
	
	
	
	
	function DoExpressCheckoutPayment()
		{
		// TODO: Error checking. ie: we require a token and payer_id here
		
		// urlencode the needed variables
		$this->urlencodeVariables();
		
		/* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
		$nvpstr = $this->generateNVPString('DoExpressCheckoutPayment');
		
		/* Construct and add any items found in this instance */
		if(!empty($this->ItemsArray))
			{
			// Counter for the total of all the items put together
			$total_items_amount = 0;
			// Go through the items array
			foreach($this->ItemsArray as $key => $value)
				{
				// Get the array of the current item from the main array
				$current_item = $this->ItemsArray[$key];
				// Add it to the request string
				$nvpstr .= "&L_NAME".$key."=".$current_item['name'].
							"&L_NUMBER".$key."=".$current_item['number'].
							"&L_QTY".$key."=".$current_item['quantity'].
							"&L_TAXAMT".$key."=".$current_item['amount_tax'].
							"&L_AMT".$key."=".$current_item['amount'];
				// Add this item's amount to the total current count
				$total_items_amount += ($current_item['amount'] * $current_item['quantity']);
				}
			// Set the amount_items for this instance and ITEMAMT added to the request string
			$this->amount_items = $total_items_amount;
			$nvpstr .= "&ITEMAMT=".$total_items_amount;
			}

		 /* Make the call to PayPal to finalize payment
			If an error occured, show the resulting errors
			*/
		$this->Response = $this->hash_call("DoExpressCheckoutPayment", $nvpstr);
		
		// decode the variables incase we still require access to them in our program
		$this->urldecodeVariables();
		
		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		
		/*
			*************
			if NO SUCCESS
			*************
			*/
		if(strtoupper($this->Response["ACK"]) != "SUCCESS")
			{
			$this->Error['TIMESTAMP']		= @$this->Response['TIMESTAMP'];
			$this->Error['CORRELATIONID']	= @$this->Response['CORRELATIONID'];
			$this->Error['ACK']				= $this->Response['ACK'];
			$this->Error['ERRORCODE']		= $this->Response['L_ERRORCODE0'];
			$this->Error['SHORTMESSAGE']	= $this->Response['L_SHORTMESSAGE0'];
			$this->Error['LONGMESSAGE']		= $this->Response['L_LONGMESSAGE0'];
			$this->Error['SEVERITYCODE']	= $this->Response['L_SEVERITYCODE0'];
			$this->Error['VERSION']			= @$this->Response['VERSION'];
			$this->Error['BUILD']			= @$this->Response['BUILD'];
			
			$this->_error				= true;
			$this->_error_ack			= $this->Response['ACK'];
			$this->ack					= 'Failure';
			$this->_error_type			= 'paypal';
			$this->_error_date			= $this->Response['TIMESTAMP'];
			$this->_error_code			= $this->Response['L_ERRORCODE0'];
			$this->_error_short_message	= $this->Response['L_SHORTMESSAGE0'];
			$this->_error_long_message	= $this->Response['L_LONGMESSAGE0'];
			$this->_error_severity_code	= $this->Response['L_SEVERITYCODE0'];
			$this->_error_version		= @$this->Response['VERSION'];
			$this->_error_build			= @$this->Response['BUILD']; 
			
			return false;
			/*
			$_SESSION['reshash']=$this->Response;
			$location = "APIError.php";
			header("Location: $location");
			*/
			}
		/*
			*************
			if SUCCESS
			*************
			*/
		elseif(strtoupper($this->Response["ACK"]) == 'SUCCESS')
			{
			/*
			Take the response variables and put them into the local class variables
			*/
			foreach($this->ResponseFieldsArray['DoExpressCheckoutPayment'] as $key => $value)
				$this->$key = $this->Response[$value];
			
			return true;
			}
		}
	
	
	
	
	function GetTransactionDetails()
		{
		/* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
		$nvpstr = $this->generateNVPString('GetTransactionDetails');
		
		/* Make the API call to PayPal, using API signature.
		   The API response is stored in an associative array called $resArray */
		$this->Response = $this->hash_call("GetTransactionDetails", $nvpstr);
		
		/* Next, collect the API request in the associative array $reqArray
		   as well to display back to the browser.
		   Normally you wouldnt not need to do this, but its shown for testing */
		
		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		
		/*
			*************
			if NO SUCCESS
			*************
			*/
		if(strtoupper($this->Response["ACK"]) != "SUCCESS")
			{
			$this->Error['TIMESTAMP']		= @$this->Response['TIMESTAMP'];
			$this->Error['CORRELATIONID']	= @$this->Response['CORRELATIONID'];
			$this->Error['ACK']				= $this->Response['ACK'];
			$this->Error['ERRORCODE']		= $this->Response['L_ERRORCODE0'];
			$this->Error['SHORTMESSAGE']	= $this->Response['L_SHORTMESSAGE0'];
			$this->Error['LONGMESSAGE']		= $this->Response['L_LONGMESSAGE0'];
			$this->Error['SEVERITYCODE']	= $this->Response['L_SEVERITYCODE0'];
			$this->Error['VERSION']			= @$this->Response['VERSION'];
			$this->Error['BUILD']			= @$this->Response['BUILD'];
			
			$this->_error				= true;
			$this->_error_ack			= $this->Response['ACK'];
			$this->ack					= 'Failure';
			$this->_error_type			= 'paypal';
			$this->_error_date			= $this->Response['TIMESTAMP'];
			$this->_error_code			= $this->Response['L_ERRORCODE0'];
			$this->_error_short_message	= $this->Response['L_SHORTMESSAGE0'];
			$this->_error_long_message	= $this->Response['L_LONGMESSAGE0'];
			$this->_error_severity_code	= $this->Response['L_SEVERITYCODE0'];
			$this->_error_version		= @$this->Response['VERSION'];
			$this->_error_build			= @$this->Response['BUILD']; 
			
			return false;
			/*
			$_SESSION['reshash']=$this->Response;
			$location = "APIError.php";
			header("Location: $location");
			*/
			}
		/*
			*************
			if SUCCESS
			*************
			*/
		elseif(strtoupper($this->Response["ACK"]) == 'SUCCESS')
			{
			/*
			Take the response variables and put them into the local class variables
			*/
			foreach($this->ResponseFieldsArray['GetTransactionDetails'] as $key => $value)
				$this->$key = $this->Response[$value];
			
			$this->getItems($this->Response);
			
			return true;
			}
		}
	
	
	
	
	function RefundTransaction()
		{
		/* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
		$nvpstr = $this->generateNVPString('RefundTransaction');
		
		/* Make the API call to PayPal, using API signature.
		   The API response is stored in an associative array called $resArray */
		$this->Response = $this->hash_call("RefundTransaction", $nvpstr);
		
		/* Next, collect the API request in the associative array $reqArray
		   as well to display back to the browser.
		   Normally you wouldnt not need to do this, but its shown for testing */
		
		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		
		/*
			*************
			if NO SUCCESS
			*************
			*/
		if(strtoupper($this->Response["ACK"]) != "SUCCESS")
			{
			$this->Error['TIMESTAMP']		= @$this->Response['TIMESTAMP'];
			$this->Error['CORRELATIONID']	= @$this->Response['CORRELATIONID'];
			$this->Error['ACK']				= $this->Response['ACK'];
			$this->Error['ERRORCODE']		= $this->Response['L_ERRORCODE0'];
			$this->Error['SHORTMESSAGE']	= $this->Response['L_SHORTMESSAGE0'];
			$this->Error['LONGMESSAGE']		= $this->Response['L_LONGMESSAGE0'];
			$this->Error['SEVERITYCODE']	= $this->Response['L_SEVERITYCODE0'];
			$this->Error['VERSION']			= @$this->Response['VERSION'];
			$this->Error['BUILD']			= @$this->Response['BUILD'];
			
			$this->_error				= true;
			$this->_error_ack			= $this->Response['ACK'];
			$this->ack					= 'Failure';
			$this->_error_type			= 'paypal';
			$this->_error_date			= $this->Response['TIMESTAMP'];
			$this->_error_code			= $this->Response['L_ERRORCODE0'];
			$this->_error_short_message	= $this->Response['L_SHORTMESSAGE0'];
			$this->_error_long_message	= $this->Response['L_LONGMESSAGE0'];
			$this->_error_severity_code	= $this->Response['L_SEVERITYCODE0'];
			$this->_error_version		= @$this->Response['VERSION'];
			$this->_error_build			= @$this->Response['BUILD']; 
			
			return false;
			/*
			$_SESSION['reshash']=$this->Response;
			$location = "APIError.php";
			header("Location: $location");
			*/
			}
		/*
			*************
			if SUCCESS
			*************
			*/
		elseif(strtoupper($this->Response["ACK"]) == 'SUCCESS')
			{
			/*
			Take the response variables and put them into the local class variables
			*/
			foreach($this->ResponseFieldsArray['RefundTransaction'] as $key => $value)
				$this->$key = $this->Response[$value];
			
			$this->getItems($this->Response);
			
			return true;
			}
		}
	
	

	
		
	/**
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	*/
	private function hash_call($methodName, $nvpStr)
		{
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_ENDPOINT);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if($this->USE_PROXY)
			curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST.":".$this->PROXY_PORT); 
	
		//NVPRequest for submitting to server
		$nvpreq = "METHOD=".urlencode($methodName)."&VERSION=".urlencode($this->VERSION)."&PWD=".urlencode($this->API_PASSWORD).
				"&USER=".urlencode($this->API_USERNAME)."&SIGNATURE=".urlencode($this->API_SIGNATURE).$nvpStr;
		
		//setting the nvpreq as POST FIELD to curl
		//echo $nvpreq;
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
	
		//getting response from server
		$response = curl_exec($ch);
	
		//convrting NVPResponse to an Associative Array
		$nvpResArray = $this->deformatNVP($response);
		$nvpReqArray = $this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray'] = $nvpReqArray;
		
		/*
			*************
			if NO SUCCESS
			*************
			*/
		if (curl_errno($ch)) 
			{
			// moving to display page to display curl errors

			$_SESSION['curl_error_no'] = curl_errno($ch) ;
			$_SESSION['curl_error_msg'] = curl_error($ch);
			
			$this->_error				= true;
			$this->ack					= 'Failure';
			$this->_error_type			= 'curl';
			$this->_error_date			= date("Y-m-d H:i:s");
			$this->_error_code			= curl_errno($ch);
			$this->_error_short_message	= 'There was an error trying to contact the PayPal servers. (curl error) See long message for details.';
			$this->_error_long_message	= curl_error($ch);
			
			return false;
			} 
		/*
			*************
			if SUCCESS
			*************
			*/
		else 
			{
			//closing the curl
			curl_close($ch);
			}
		
		return $nvpResArray;
		}
		
		
		
		/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
		  * It is usefull to search for a particular key and displaying arrays.
		  * @nvpstr is NVPString.
		  * @nvpArray is Associative Array.
		  */
		private function deformatNVP($nvpstr)
			{
			$intial=0;
			$nvpArray = array();
			
			while(strlen($nvpstr))
				{
				//postion of Key
				$keypos= strpos($nvpstr,'=');
				//position of value
				$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
		
				/*getting the Key and Value values and storing in a Associative Array*/
				$keyval=substr($nvpstr,$intial,$keypos);
				$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
				//decoding the respose
				$nvpArray[urldecode($keyval)] =urldecode( $valval);
				$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
				}
				
			return $nvpArray;
			}
		
		
		
		
		/** This function will add an item to the itemArray for use in doDirectPayment and doExpressCheckoutPayment
		  */
		public function addItem($name, $number, $quantity, $amount_tax, $amount)
			{
			$new_item =  array(
					'name' => $name, 
					'number' => $number, 
					'quantity' => $quantity, 
					'amount_tax' => $amount_tax, 
					'amount' => $amount);
			
			$this->ItemsArray[] = $new_item;
			
			// TODO: Should recalculate and set $this->amount_items after every new item is added. Or is this done on each request?
			}
		
		
		
		private function getItems($passed_response)
			{
			// Clear any current items
			$this->ItemsArray = '';
			
			// Get the items if there are any
			// Start this off by checking for a first item
			if(!empty($passed_response['L_NAME0']) OR !empty($passed_response['L_NUMBER0']) OR !empty($passed_response['L_QTY0']))
				{
				$i = 0;
				// Start a loop to get all the items (up to 200)
				// We'll break out of it if we stop finding items
				while($i < 200)
					{
					// One of the Name, Number, and Qty fields may be empty, so check all of them
					//   and if any of them are filled, then we have an item
					if(!empty($passed_response['L_NAME'.$i]) OR !empty($passed_response['L_NUMBER'.$i]) OR !empty($passed_response['L_QTY'.$i]))
						{
						$new_item =  array(
								'name' => $passed_response['L_NAME'.$i], 
								'number' => $passed_response['L_NUMBER'.$i], 
								'quantity' => $passed_response['L_QTY'.$i], 
								'amount_tax' => $passed_response['L_TAXAMT'.$i], 
								'amount' => $passed_response['L_AMT'.$i]);
						
						$this->ItemsArray[] = $new_item;
						$i++;
						}
					else
						break;
					}
				}
			}
		
		
		
		private function generateNVPString($type)
			{
			$temp_nvp_str = '';
			// Go through the selected RequestFieldsArray and create the request string
			//    based on whether the field is required or filled
			// TODO: return error if required field is empty?
			foreach($this->RequestFieldsArray[$type] as $key => $value)
				{
				if($value['required'] == 'yes')
					$temp_nvp_str .= '&'.$value['name'].'='.$this->$key;
				elseif(!empty($this->$key))
					$temp_nvp_str .= '&'.$value['name'].'='.$this->$key;
				}
			return $temp_nvp_str;
			}
		
		
		
		/** This function encodes all applicable variables for transport to PayPal
		  */
		private function urlencodeVariables()
			{
			// Decode all specified variables
			$this->payment_type			= urlencode($this->payment_type);
			
			$this->email		= urlencode($this->email);
			$this->first_name			= urlencode($this->first_name);
			$this->last_name			= urlencode($this->last_name);
			$this->credit_card_type		= urlencode($this->credit_card_type);
			$this->credit_card_number	= urlencode($this->credit_card_number);
			$this->expire_date_month		= urlencode($this->expire_date_month);
			
			// Month must be padded with leading zero
			$this->expire_date_month	= urlencode(str_pad($this->expire_date_month, 2, '0', STR_PAD_LEFT));
			
			$this->expire_date_year	= urlencode($this->expire_date_year);
			$this->cvv2_code		= urlencode($this->cvv2_code);
			$this->address1			= urlencode($this->address1);
			$this->address2			= urlencode($this->address2);
			$this->city				= urlencode($this->city);
			$this->state			= urlencode($this->state);
			$this->postal_code		= urlencode($this->postal_code);
			$this->country_code		= urlencode($this->country_code);
			
			$this->currency_code	= urlencode($this->currency_code);
			$this->ip_address		= urlencode($this->ip_address);
			
			$this->shipping_name			= urlencode($this->shipping_name);
			$this->shipping_address1		= urlencode($this->shipping_address1);
			$this->shipping_address2		= urlencode($this->shipping_address2);
			$this->shipping_city			= urlencode($this->shipping_city);
			$this->shipping_state			= urlencode($this->shipping_state);
			$this->shipping_postal_code		= urlencode($this->shipping_postal_code);
			$this->shipping_country_code	= urlencode($this->shipping_country_code);
			$this->shipping_phone_number			= urlencode($this->shipping_phone_number);
			
			$this->amount_total		= urlencode($this->amount_total);
			$this->amount_shipping	= urlencode($this->amount_shipping);
			$this->amount_tax		= urlencode($this->amount_tax);
			$this->amount_handling	= urlencode($this->amount_handling);
			$this->amount_items		= urlencode($this->amount_items);
			
			$this->token		= urlencode($this->token);
			$this->payer_id		= urlencode($this->payer_id);
			

	
			if(!empty($this->ItemsArray))
				{
				// Go through the items array
				foreach($this->ItemsArray as $key => $value)
					{
					// Get the array of the current item from the main array
					$current_item = $this->ItemsArray[$key];
					// Encode everything
					// TODO: use a foreach loop instead
					$current_item['name'] = urlencode($current_item['name']);
					$current_item['number'] = urlencode($current_item['number']);
					$current_item['quantity'] = urlencode($current_item['quantity']);
					$current_item['amount_tax'] = urlencode($current_item['amount_tax']);
					$current_item['amount'] = urlencode($current_item['amount']);
					// Put the encoded array back in the item array (replaces previous array)
					$this->ItemsArray[$key] = $current_item;
					}
				}
			}
		
		/** This function DEcodes all applicable variables for use in application/database
		  */
		private function urldecodeVariables()
			{
			// Decode all specified variables
			$this->payment_type			= urldecode($this->payment_type);
			
			$this->email		= urldecode($this->email);
			$this->first_name			= urldecode($this->first_name);
			$this->last_name			= urldecode($this->last_name);
			$this->credit_card_type		= urldecode($this->credit_card_type);
			$this->credit_card_number	= urldecode($this->credit_card_number);
			$this->expire_date_month		= urldecode($this->expire_date_month);
			
			// Month must be padded with leading zero
			$this->expire_date_month	= urldecode(str_pad($this->expire_date_month, 2, '0', STR_PAD_LEFT));
			
			$this->expire_date_year	= urldecode($this->expire_date_year);
			$this->cvv2_code		= urldecode($this->cvv2_code);
			$this->address1			= urldecode($this->address1);
			$this->address2			= urldecode($this->address2);
			$this->city				= urldecode($this->city);
			$this->state			= urldecode($this->state);
			$this->postal_code		= urldecode($this->postal_code);
			$this->country_code		= urldecode($this->country_code);
			
			$this->currency_code	= urldecode($this->currency_code);
			$this->ip_address		= urldecode($this->ip_address);
			
			$this->shipping_name				= urldecode($this->shipping_name);
			$this->shipping_address1			= urldecode($this->shipping_address1);
			$this->shipping_address2			= urldecode($this->shipping_address2);
			$this->shipping_city				= urldecode($this->shipping_city);
			$this->shipping_state				= urldecode($this->shipping_state);
			$this->shipping_postal_code			= urldecode($this->shipping_postal_code);
			$this->shipping_country_code		= urldecode($this->shipping_country_code);
			$this->shipping_phone_number	= urldecode($this->shipping_phone_number);
			
			$this->amount_total		= urldecode($this->amount_total);
			$this->amount_shipping	= urldecode($this->amount_shipping);
			$this->amount_tax		= urldecode($this->amount_tax);
			$this->amount_handling	= urldecode($this->amount_handling);
			$this->amount_items		= urldecode($this->amount_items);
			
			$this->token		= urldecode($this->token);
			$this->payer_id		= urldecode($this->payer_id);
			
			
			if(!empty($this->ItemsArray))
				{
				// Go through the items array
				foreach($this->ItemsArray as $key => $value)
					{
					// Get the array of the current item from the main array
					$current_item = $this->ItemsArray[$key];
					// Decode everything
					// TODO: use a foreach loop instead
					$current_item['name'] = urldecode($current_item['name']);
					$current_item['number'] = urldecode($current_item['number']);
					$current_item['quantity'] = urldecode($current_item['quantity']);
					$current_item['amount_tax'] = urldecode($current_item['amount_tax']);
					$current_item['amount'] = urldecode($current_item['amount']);
					// Put the decoded array back in the item array (replaces previous array)
					$this->ItemsArray[$key] = $current_item;
					}
				}
			}




// END CLASS
}




?>