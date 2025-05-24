<?php

namespace Xudid\Mail;

use TypeError;

/**
 * Class Recepient
 * @package Xudid\Mail
 */
class Recepient
{
    const FROM = 'from';
    const TO = 'to';
    const CC = 'cc';
    const BCC = 'bcc';
    const REPLY = 'reply';

    private string $type = self::TO;

    public function __construct(protected string $mail, protected string $name = '')
    {
        if (!filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
			throw new TypeError('mail parameter must be a valid mail address');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function from(): static
    {
        $this->type = self::FROM;
        return $this;
    }

    public function cc(): static
    {
        $this->type = self::CC;
        return $this;
    }

    public function bcc(): static
    {
        $this->type = self::BCC;
        return $this;
    }

    public function reply(): static
    {
        $this->type = self::REPLY;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
