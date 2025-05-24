<?php

namespace Xudid\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class Mailer
 * @package Xudid\Mail
 */
class Mailer
{
    public function __construct(
		private PHPMailer $mail,
		Account $account,
	)
    {
        $this->mail->isSMTP();
        $this->mail->Host = $account->getHost();
        $this->mail->Username = $account->getUserName();
        $this->mail->Password = $account->getPassword();
        $this->mail->Port = $account->getPort();
    }

    public function smtpSecure(bool $secure = false): static
    {
        if (in_array($secure, [false, 'tls', 'ssl']))
            $this->mail->SMTPSecure = $secure;
        return $this;
    }

    public function smtpAutoTLS(bool $auto = false): static
    {
        $this->mail->SMTPAutoTLS = $auto;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(Message $message)
    {
        // html message
        $this->mail->isHTML();
        $fromRecipient = $message->getFrom();
        $this->mail->setFrom($fromRecipient->getMail(), $fromRecipient->getName());
        foreach ($message->getRecipients() as $recipient) {
            switch ($recipient->getType()) {
                case Recepient::TO:
                    $this->mail->addAddress($recipient->getMail(), $recipient->getName());
                    break;
                case Recepient::CC:
                    $this->mail->addCC($recipient->getMail(), $recipient->getName());
                    break;
                case Recepient::BCC:
                    $this->mail->addBCC($recipient->getMail(), $recipient->getName());
                    break;
                case Recepient::REPLY:
                    $this->mail->addReplyTo($recipient->getMail(), $recipient->getName());
                    break;
                default:
                    $this->mail->addAddress($recipient->getMail(), $recipient->getName());

            }
        }
        $this->mail->Body = $message->getContent();

        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $debug = '';
        /*level 1 = client; will show you messages sent by the client
        level 2  = client and server; will add server messages, it’s the recommended setting.
        level 3 = client, server, and connection; will add information about the initial information, might be useful for discovering STARTTLS failures
        level 4 = low-level information.*/
        $this->mail->SMTPDebug = 4;
        $this->mail->Debugoutput = function ($str, $level) use (&$debug) {
            $debug .= "$level: $str\n";
        };
        if ($this->mail->send()) {
            $this->clearAll();
            return true;
        } else {
            $this->clearAll();
            throw new Exception($debug . '<br>' . $this->mail->ErrorInfo);
        }
    }

    private function clearAll()
    {
        $this->mail->clearAllRecipients();
        $this->mail->clearAttachments();
        $this->mail->clearCustomHeaders();
    }
}
