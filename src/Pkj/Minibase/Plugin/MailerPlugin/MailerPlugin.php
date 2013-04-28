<?php
namespace Pkj\Minibase\Plugin\MailerPlugin;

use Minibase\Plugin\Plugin;
use Minibase\MB;

class MailerPlugin extends Plugin {
	
	public function defaultConfig () {
		return array(
				'transport' => 'mail',
				'sendmailCommand' => '/usr/sbin/sendmail -bs',
				'mailParams' => '-f%s',
				'transports' => array()
				);
	}
	
	
	protected function getTransport ($cfg) {
		$transport = null;
		switch($cfg['transport']){
			case "smtp":
				$cfg['host'] = isset($cfg['host']) ? $cfg['host'] : "localhost";
				$cfg['port'] = isset($cfg['port']) ? $cfg['port'] : 25;
				$cfg['encryption'] = isset($cfg['encryption']) ? $cfg['encryption'] : null;
				
				$transport = \Swift_SmtpTransport::newInstance($cfg['host'], $cfg['port'], $cfg['encryption']);
				
				if (isset($cfg['auth_mode'])) {
					$transport->setAuthMethod(strtoupper($cfg['auth_mode']));
				}
				if (isset($cfg['username'])) {
					$transport->setUsername($cfg['username']);
				}
				if (isset($cfg['password'])) {
					$transport->setPassword($cfg['password']);
				}
				
				
				break;
			case "sendmail":
				$transport = \Swift_SendmailTransport::newInstance($cfg['sendmailCommand']);
				break;
			case "mail":
				$transport = \Swift_MailTransport::newInstance($cfg['mailParams']);
				break;
			case "loadbalanced":
			case "failover":
				$transports = array();
				foreach($cfg['transports'] as $t) {
					$transports[] = $this->getTransport($t);
				}

				if ($cfg['transport'] === 'loadbalanced') {
					$transport = \Swift_LoadBalancedTransport::newInstance($transports);
				} else {
					$transport = \Swift_FailoverTransport::newInstance($transports);
				}
				break;
		}
		return $transport;
	}
	
	
	public function setup() {
		$cfg = $this->config;
		
		$this->mb->plugin("mailer", function () use ($cfg) {
			$transport = $this->getTransport($cfg);
			$swift = new \Swift_Mailer($transport);
			return $swift;
		});
	}
	
	public function start () {
					
	}
	
}