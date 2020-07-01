<?php

include('framework/libs/PHPMailer-master/PHPMailerAutoload.php');

class mailer{

	private static $host;							//host poczty np. smtp.wp.pl
	private static $username;						//login do poczty
	private static $frommail;						//email nadawcy
	private static $fromname;						//nazwa nadawcy
	private static $reply_to_mail=false;
	private static $reply_to_name=false;
	private static $pass;							//hasło do poczty
	private static $port;	
	private static $smtp_secure;	
	private static $a_addresses = array();			//tablica z adresatami
	private static $a_attachments = array();		//tablica załączników
	private static $a_images = array();				//tablica z załączonymi grafikami
	private static $charset = 'utf-8';				//kodowanie
	
	public static function set_host($i_host){ self::$host=$i_host; }
	public static function set_username($i_username){ self::$username=$i_username; }
	public static function set_pass($i_pass){ self::$pass=$i_pass; }
	public static function set_fromname($i_fromname){ self::$fromname=$i_fromname; }
	public static function set_frommail($i_frommail){ self::$frommail=$i_frommail; }
	public static function set_reply_to($i_mail,$i_name){ self::$reply_to_mail=$i_mail;self::$reply_to_name=$i_name; }
	public static function set_charset($i_charset){ self::$charset=$i_charset; }
	public static function set_port($i_port){ self::$port=$i_port; }
	public static function set_smtp_secure($i_smtp_secure){ self::$smtp_secure=$i_smtp_secure; }
	
	public static function add_address($i_address){ self::$a_addresses[]=$i_address; }
	public static function add_image($path,$id)
	{
		$a_img['path']=$path;
		$a_img['id']=$id;
		self::$a_images[]=$a_img;
	}

	public static function add_attachment($path,$name,$encoding='base64',$type='application/octet-stream',$disposition=false)
	{
		$a_attachment['path']=$path;
		$a_attachment['name']=$name;
		$a_attachment['encoding']=$encoding;
		$a_attachment['type']=$type;
		$a_attachment['disposition']=$disposition;
		self::$a_attachments[]=$a_attachment;

	}
	
	public static function send($subject='',$content='',$a_reply_to=false,$is_html=false)
	{
		set_time_limit(300);
		$mail = new PHPMailer;
		//$mail->SMTPDebug = 1;
		$mail->isSMTP(); 									// Set mailer to use SMTP
		$mail->Host = self::$host;							// Specify main and backup server
		$mail->SMTPAuth = true;								// Enable SMTP authentication
		$mail->Username = self::$username;                  // SMTP username
		$mail->Port = self::$port;						// SMTP password
		$mail->Password = self::$pass;
		$mail->SMTPSecure = self::$smtp_secure;
		$mail->SMTPKeepAlive = true;  
	
		$mail->From = self::$frommail;
		$mail->FromName = self::$fromname;
		
		$mail->CharSet = self::$charset;
		$mail->SetLanguage("pl", "phpmailer/language/");
		
		if(self::$reply_to_mail)
			$mail->AddReplyTo(self::$reply_to_mail, self::$reply_to_name);

		foreach(self::$a_images as $a_img)
			$mail->addEmbeddedImage($a_img['path'],$a_img['id']); 
		
		foreach(self::$a_attachments as $a_at)
			$mail->addStringAttachment(file_get_contents($a_at['path']),$a_at['name'],$a_at['encoding'],$a_at['type']); 
		
		if($a_reply_to)
			$mail->addReplyTo($a_reply_to['email'], $a_reply_to['name']);
		
		$mail->isHTML($is_html);
		
		if(!$is_html)
			$mail->ContentType = 'text/plain'; 

		$mail->Subject = $subject;
		$mail->Body = $content;
		
		foreach(self::$a_addresses as $address)
		{
			$mail->addAddress($address);  // Add a recipient
			
			if(!$mail->send())
			{
			   var_dump(('Błąd wysyłki email: '.$mail->ErrorInfo));
			   app::err($mail->ErrorInfo);
			   return false;
			}
			$mail->ClearAddresses();  
			$mail->ClearAllRecipients();
			
			if(count(self::$a_addresses)>10)
				sleep(1);
		}
		
		self::$a_addresses = array();
		
		$mail->SmtpClose();
		app::ok('Wysłano');
		return $mail;
	}
}

?>