<?php 

function get($url,$header) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

function post($url,$header,$data) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

function dashboard() {
	$header = [
		'content-type: text/html; charset=UTF-8',
		'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/',
		'accept-language: es-US,es-419;q=0.9,es;q=0.8,en;q=0.7',
		'cookie: _ga=GA1.2.1145820851.1622907419; __gads=ID=19e80263240c5a6c-2201323dd6b30002:T=1622907419:RT=1622907419:S=ALNI_Mba8uBAox_76ICJgmCL0zoYJm9gBA; _fbp=fb.1.1622907419268.958686498; _gid=GA1.2.51028348.1623027409; ci_session=70b702odcoefecoldsbtvpako7lbr9b9; siteuser=e1623079884K5305w88976; userid=9ca91ab26588be583aebe02f9392493087aa25cbb0c200622bb8338b060e88d3e3c528826f36dacccd1140a0d343beda72d56c293bf5e1459616c354d410fd81cyE1AvoiHdpg%2FDN8uf%2B1H2l1d%2FHzD3EkvR%2FNsrIzeT0%3D; TawkConnectionTime=1623079928518',
		'referer: https://unmined.io/member/dashboard',
		'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36'
	];

	$url = 'https://unmined.io/member/dashboard';

	$dashboard = get($url,$header);
	return $dashboard;
}

function collect() {

	$header = [
		'accept: application/json, text/javascript, */*; q=0.01',
		'accept-language: es-US,es-419;q=0.9,es;q=0.8,en;q=0.7',
		'content-type: application/x-www-form-urlencoded; charset=UTF-8',
		'cookie: _ga=GA1.2.1145820851.1622907419; __gads=ID=19e80263240c5a6c-2201323dd6b30002:T=1622907419:RT=1622907419:S=ALNI_Mba8uBAox_76ICJgmCL0zoYJm9gBA; _fbp=fb.1.1622907419268.958686498; _gid=GA1.2.51028348.1623027409; siteuser=e1623079884K5305w88976; userid=9ca91ab26588be583aebe02f9392493087aa25cbb0c200622bb8338b060e88d3e3c528826f36dacccd1140a0d343beda72d56c293bf5e1459616c354d410fd81cyE1AvoiHdpg%2FDN8uf%2B1H2l1d%2FHzD3EkvR%2FNsrIzeT0%3D; ci_session=ahalsipdtui7u351itsnndhqjbd5tfjf;TawkConnectionTime=1623083672574',
		'origin: https://unmined.io',
		'referer: https://unmined.io/member/dashboard',
		'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36',
		'x-requested-with: XMLHttpRequest'
	];
	$url = 'https://unmined.io/member/collect_coins_to_wallet';
	$data = 'reqamount=0.00000060&minamount=0.00000060';

	$collect = post($url,$header,$data);
	return $collect;
}

while(true) {
	$dashboard = dashboard();

	$balance = explode('<input type="hidden" id="newtext" value="', $dashboard)[1];
	$balance = explode('">', $balance)[0];

	$total = explode('<p><b>BTC : ', $dashboard)[2];
	$total = explode(' </b>($ 0.000)</p>', $total)[0];

	echo "Balance : ".$balance." BTC   |   Total Wallet : ".$total." BTC\n";

	if($balance === '0.000000600') {
		while (true) {
			sleep(60);
			echo "\nClaiming balance...\n";
			$collect = collect();
			$collect = json_decode($collect, true);
			echo "Message : ".$collect['result']." claim\n";

			// menghentikan perulangan jika claim success
			if($collect['result'] === 'success') {
				break;
			}
		}
	}

	sleep(60);
}














