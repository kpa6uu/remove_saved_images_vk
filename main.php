<?

/**
 * Call VK API method
 * @param string $method
 * @param array $data
 * @return mixed
 */
function method_request($method, array $data) {
	// https://oauth.vk.com/authorize?client_id=5667385&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=photos,offline&response_type=token&v=5.58&state=123456
	$secret_token = ''; // VK API ACCESS TOKEN

	$data['v'] = '5.58';
	$data['access_token'] = $secret_token;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/'.$method);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result, true);
}

$pic_list = method_request('photos.get', [
	'album_id' => 'saved',
	'count' => '1000',
])['response']['items'];

echo 'Total photos count: '.count($pic_list)."\n";

foreach ($pic_list as $pic) {
	$pic_id = $pic['id'];
	$result = method_request('photos.delete', ['photo_id' => $pic_id,]);
	echo ($result['response']) ? 'Photo #'.$pic_id.' was successfully removed from your account' : 'Photo #'.$pic_id.' [UNKNOWN ERROR]';
	echo "\n";
	sleep(1);
}
