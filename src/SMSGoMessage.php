<?php

namespace NotificationChannels\SMSGo;

class SMSGoMessage
{
    public $delay;

    public $content;

    public $encoding = 'BIG5';

    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public static function create(string $content = ''): self
    {
        return new static($content);
    }

    public function delay(\DateTimeInterface $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function encoding(string $encoding): self
    {
        $this->encoding = $encoding;

        return $this;
    }
}
