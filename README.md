# Mailer Plugin for Minibase

Adds mailing support for Minibase application. Uses the [Swiftmailer](http://swiftmailer.org/) library to send emails. Easy to configure to forexample use *gmail* as SMTP server.



## Install

```json
{
  "require":{
	     "pkj/minibase-plugin-mailer": "dev-master"
	}
}

```

## Setup

Init the plugin

```php
$mb->initPlugins(array(
	'Pkj\Minibase\Plugin\MailerPlugin\MailerPlugin' => array(
		// This configures Swiftmailer to use Gmail as smtp.
		'transport' => 'smtp',
		'encryption' => 'ssl',
		'auth_mode' => 'login',
		'host' => 'smtp.gmail.com',
		'username' => 'your gmail username',
		'password' => 'your gmail password'
	)
));
```


#### Possible configuration keys:

- transport (smtp, mail, sendmail, loadbalanced or failover)
- username
- password
- host
- port
- encryption (tls, or ssl)
- auth_mode (plain, login, or cram-md5)
- sendmailCommand (if using sendmail transport, you can customize the default command `/usr/sbin/sendmail -bs`)
- mailParams (if you want to customize the mailparams, used by the mail transport `-f%s`)
- transports (array of transport configuratioins, used by loadbalanced and failover transport.)


## Send emails.

You can send emails from forexample controllers (note `$this->mb`):

```php
$message = \Swift_Message::newInstance()
        ->setSubject('Hello Email')
        ->setFrom('send@example.com')
        ->setTo('recipient@example.com')
        ->setBody("Hello World!");

$this->mb->mailer->send($message);
```
