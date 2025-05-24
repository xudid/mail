<?php

namespace Xudid\Mail;

/**
 * Class Message
 * @package Xudid\Mail
 */
class Message
{
    private string $type = 'HTML';
    private array $recipients = [];

    public function __construct(
		protected Recepient $from,
		protected string $subject,
		protected string $content,
		Recepient $to,
	) {
        $this->recipients[] = $to;
    }

    public function addRecipient(Recepient $recipient): static
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    public function recipients(Recepient ...$recepients): static
    {
        if (is_array($recepients)) {
            $this->recipients = array_merge($this->recipients, $recepients);
        } else {
            foreach ($recepients as $recepient) {
                $this->recipients[] = $recepient;
            }
        }
        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFrom(): Recepient
    {
        return $this->from;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
