<?php

/**
 * 通用辅助方法
 * 
*/

class helper_common {

	public static $domain;

	public static function be_true($data = '', $msg = '') {
		return array(
			'status' => true, 
			'data' => $data,
			'msg' => $msg
		);
	}

	public static function be_false($msg = '', $data = '') {
		return array(
			'status' => false, 
			'data' => $data,
			'msg' => $msg
		);
	}

	public static function uri($url = '') {
		if (empty(self::$domain)) {
			self::$domain = Yaf_Registry::get("config")->customer->domain;
		}
		return 'http://'.self::$domain.'/'.trim($url, '/').((!$url || strpos($url, '.')) ? '' : '/');
	}

	public static function redirect($uri, $message = '', $seconds = 0, $type = 'succeed') {
		if ($seconds == 0) {
			//表示永远停留页面
			header("Location: ".self::uri($uri));
		}
		exit;
	}

	public static function authcode($string) {
		$key = Yaf_Registry::get("config")->customer->key;
		$string .= $key;
		$password = md5(substr(md5($string), 5, 20));
		return $password;
	}

	public static function array_stripslashes($string) {
		if(empty($string)) return $string;
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = tiny_stripslashes($val);
			}
		} else {
			$string = stripslashes($string);
		}
		return $string;
	}

	public static function alert($message, $level = 'danger') {
		include ROOT_PATH.'/theme/'.theme.'/public/alert.html';
	}

	public static function system_error($message) {
		ob_end_clean();
		ob_start();

		$host = $_SERVER['HTTP_HOST'];
		echo <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>$host - System Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
<style type="text/css">
<!--
body {
    background-color: #fff;
    margin: 40px;
    font: 13px/20px normal Helvetica, Arial, sans-serif;
    color: #4F5155;
}

h1 {
    color: #000;
    font-weight: 800;
    font-size: 36px;
	line-height: 40px;
}

code {
    font-family: Consolas, Monaco, Courier New, Courier, monospace;
    font-size: 14px;
    color: #333;
    display: block;
}

#body {
    margin: 10% 15px;
}

p.footer {
    font-size: 11px;
    border-top: 1px solid #D0D0D0;
    line-height: 32px;
}
-->
</style>
</head>
<body>
<div id="body">
<h1>$message</h1>
EOT;
		if (Yaf_Registry::get('config')->customer->debug) {
			if($phpmsg = debug_backtrace()) {
				if(is_array($phpmsg)) {
					foreach($phpmsg as $k => $msg) {
						$k++;
						echo '<code>'.$k.'. ';
						if (isset($msg['file'])) {
							echo '[Line: '.$msg['line'].']'.$msg['file'];
						}
						echo '('.$msg['function'].')</code>';
					}
				} else {
					echo $phpmsg;
				}
			}
		}
		echo <<<EOT
<p class="footer">jianjia.club Version 0.0.1</p>
</body>
</html>
EOT;
		exit();
	}
}