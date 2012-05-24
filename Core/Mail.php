<?php
namespace Core;
use \PHPMailer,
	\Exception;

require LIB . 'PHPMailer/class.phpmailer.php';

/**
 * Sends emails after configuration with Mail::configure()
 * @author Renato S. Martins <smartins.renato@gmail.com>
 * @example
 * Mail::configure(array(
 *     'from'    => 'my-email@email.com',
 *     'subject' => 'Prepended subject', // will be enclosed in square brackets
 *     'smtp'    => array(
 *         'server'   => 'smtp.email.com',
 *         'port'     => 25,
 *         'username' => 'username',
 *         'password' => 'password'
 *     )
 * ));
 * Mail::send(
 *     array('a-user@email.com', 'another-user@email.com'),
 *     'Subject',
 *     'My message to you',
 *     Mail::PRIORITY_HIGH  //optional
 * );
 * Mail::send(
 *     array('lonely-user@email.com'),
 *     'Other email subject',
 *     'Little message'
 * );
 */
class Mail
{
	const PRIORITY_HIGH = 1;
	const PRIORITY_NORMAL = 3;
	const PRIORITY_LOW = 5;
	
	static private $_config;
	
	/**
	 * @static
	 * @param array $config - Key 'from' is required
	 * @return void
	 */
	static public function configure(array $config)
	{
		self::$_config = $config;
	}
	
	/**
	 * Send an email according to parameters and configuration set in Mail::configure
	 * @static
	 * @param array $to
	 * @param string $subject
	 * @param string $message
	 * @param int $priority (Mail::PRIORITY_HIGH, Mail::PRIORITY_NORMAL, Mail::PRIORITY_LOW)
	 * @return bool
	 * @throws Exception
	 */
	static public function send(array $to, $subject, $message, $priority = 3)
	{
		if (isset(self::$_config['from']) === false) {
			throw new Exception('Mail misses config value to "from"');
		}
		
		$mail = new PHPMailer();
		$mail->CharSet = 'utf-8';
		$mail->Priority = $priority;
		$mail->SetFrom(self::$_config['from'], 'Webtool');
		
		foreach ($to as $email) {
			$mail->AddAddress($email);
		}
		
		if (isset(self::$_config['reply'])) {
			$mail->AddReplyTo(self::$_config['reply']);
		}
		if (isset(self::$_config['subject'])) {
			$subject = '[' . self::$_config['subject'] . '] - ' . $subject;
		}
		
		$mail->Subject = $subject;
		$mail->Body = $message;
		
		if (isset(self::$_config['smtp'])) {
			$smtp = self::$_config['smtp'];
			$mail->IsSMTP();
			if (isset($smtp['server']) === false) {
				throw new Exception('Mail misses config value to "smtp"/"server"');
			}
			$mail->Host = $smtp['server'];
			if (isset($smtp['port'])) {
				$mail->Port = $smtp['port'];
			}
			if (isset($smtp['username']) && isset($smtp['password'])) {
				$mail->SMTPAuth = true;
				$mail->Username = $smtp['username'];
				$mail->Password = $smtp['password'];
			}
		}
		
		return $mail->Send();
	}
}
