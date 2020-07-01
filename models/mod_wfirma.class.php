<?php

class mod_wfirma extends db
{
	private static $key = 'e0c924f290ea1ee5477203a11a1bcbd2';
	private static $secret = '61332cb70992b6e4d12dd55c5dc5e2fd';
	
	public static function requestToken()
	{
	    $oAuth = new OAuth(self::$key, self::$secret, OAUTH_SIG_METHOD_PLAINTEXT);
	
	    $scope = 'invoices-read,invoices-write,contractors-read,contractors-write';
	    $callback = 'https://legitymacje1.loca.pl/zamowienia/wystaw_fakture'.$tokenInfo['oauth_token'];
	
	    try {
	        $tokenInfo = $oAuth->getRequestToken(
	            'https://wfirma.pl/oauth/requestToken?scope=' . $scope,
	            $callback,
	            'GET'
	        );
	
	      $_SESSION['oauthSecret'] = $tokenInfo['oauth_token_secret'];
		  header('Location: https://wfirma.pl/oauth/authorize?oauth_token=' . $tokenInfo['oauth_token']);
	    } catch (OAuthException $exception) {
	    	echo 1;var_dump($exception);
	    }
	}
	
	function accessToken($oauth_token,$oauth_verifier)
	{
	    $oAuth = new OAuth(self::$key, self::$secret, OAUTH_SIG_METHOD_PLAINTEXT);
	
	    $oAuth->setToken($oauth_token, $_SESSION['oauthSecret']);
	    unset($_SESSION['oauthSecret']);
	
	    try {
	        $tokenInfo = $oAuth->getAccessToken(
	            'https://wfirma.pl/oauth/accessToken?oauth_verifier=' . $oauth_verifier
	        );
	    } catch (OAuthException $exception) {
	        // Wystąpił błąd podczas autoryzacji.
	        echo 2;var_dump($exception);
	        return;
	    }
	
	    $_SESSION['oauth_token_secret'] = $tokenInfo['oauth_token_secret'];
	    $_SESSION['oauth_token'] = $tokenInfo['oauth_token'];
	}
	
	public static function oauthRequest($action, $data = []) {
	    $oAuth = new OAuth(self::$key, self::$secret, OAUTH_SIG_METHOD_PLAINTEXT);
	    $oAuth->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	
	    try {
	        $oAuth->fetch(
	            'https://api2.wfirma.pl/' . $action,
	            !empty($data) ? $data : '',
	            OAUTH_HTTP_METHOD_POST
	        );
	    } catch (Exception $exception) {
	    	echo 3;var_dump($exception);
	        return false;
	    }
	
	    return $oAuth->getLastResponse();
	}
	
	public static function get()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api2.wfirma.pl/invoices/download/36669731');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		curl_setopt($ch, CURLOPT_USERPWD, 'oiskaras@wp.pl' . ':' . 'osamabinladen1');
		$result = curl_exec($ch);
		file_put_contents('faktura.pdf', $result);
		return $result;
	}
	
}

?>