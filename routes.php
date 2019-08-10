<?php	

	Route::get('/mail', function(){
		$order = \Pixel\Shop\Models\Order::find(24);
        // return view('pixel.shop::mail.new-order', ['order' => $order] );

        App::setLocale('es');

        return Mail::outputView('pixel.shop::mail.order_awaitpay', [
            'order' => $order,
            'backend_url' => Backend::url('pixel/shop/orders')
        ]);
	});

	// UPLOAD AVATAR
	Route::post('/api/pixel/shop/avatar', function () {
		if (class_exists("\RainLab\User\Models\User")) {
			if($model = \RainLab\User\Models\User::find(Input::get('user'))){
				$model->avatar = Input::file('upload');
				$model->save();
			}
		}else{
			return false;
		}
	});

	// VERIFY PIXELPAY RESPONSE
	Route::post('/api/pixel/shop/pixelpay-response', function () {
		$eventLog = new \System\Models\EventLog();
		$data = array();
		$data["name"] = "Payment PixelPay Response";
		$data["post"] = Request::json() ?? post();
		$eventLog->message = json_encode($data);
		$eventLog->save();

		if(Request::isJson() && Request::json('order')){
			$order_id = Request::json('order');
			$order = \Pixel\Shop\Models\Order::find($order_id);

			if(!$order)
				return;

			$order->status = 'await_fulfill';

			$order->paid_at = \Carbon\Carbon::now();
			$order->is_paid = true;
			$order->is_confirmed = true;
			$order->response = post();

			$hash = [
				$order->id,
				\Pixel\Shop\Models\GatewaysSettings::get('pixelpay_app'),
				\Pixel\Shop\Models\GatewaysSettings::get('pixelpay_hash')
			];

			$hash = implode('|', $hash);
			$hash = md5($hash);

			if($hash == Request::json('payment_hash'))
				$order->save();

			$order->reduceInventory();
			$order->sendNotification();
		}
	});

	// VERIFY PAYPAL RESPONSE
	Route::post('/api/pixel/shop/paypal-response', function () {
		$eventLog = new \System\Models\EventLog();
		$data = array();
		$data["name"] = "Payment PayPal Response";
		$data["post"] = post();
		$eventLog->message = json_encode($data);
		$eventLog->save();

		if (paypallValidateIPN()) {
			$order_id = post("custom");
			$order = \Pixel\Shop\Models\Order::find($order_id);
			$order->status = 'await_fulfill';
			
			$order->paid_at = \Carbon\Carbon::now();
			$order->is_paid = true;
			$order->is_confirmed = true;
			$order->response = post();
			
			$order->save();

			$order->reduceInventory();
			$order->sendNotification();
		}
	});
	
   	// PAYPAL VALIDATE IPN STATUS
	function paypallValidateIPN() {
		define("DEBUG", 0);

		define("USE_SANDBOX", 0);
		define("LOG_FILE", temp_path()."/ipn.log");

		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		
		$req = 'cmd=_notify-validate';
		if (function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}
		// Post IPN data back to PayPal to validate the IPN data is genuine
		// Without this step anyone can fake IPN data
		if (USE_SANDBOX == true) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		$ch = curl_init($paypal_url);
		if ($ch == FALSE) {
			return FALSE;
		}
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);
		if (DEBUG == true) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) {
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
			}
			curl_close($ch);
			return false;
		} else {
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "HTTP request of validation request:" . curl_getinfo($ch, CURLINFO_HEADER_OUT) . " for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
				error_log(date('[Y-m-d H:i e] ') . "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
			}
			curl_close($ch);
		}

		$tokens = explode("\r\n\r\n", trim($res));
		$res = trim(end($tokens));
		if (strcmp($res, "VERIFIED") == 0) {
			return true;

			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "Verified IPN: $req " . PHP_EOL, 3, LOG_FILE);
			}
		} else if (strcmp($res, "INVALID") == 0) {
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
			}
		}

		return false;
	}
?>