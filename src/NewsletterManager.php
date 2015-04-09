<?php
namespace beeare\DependencyInjection;

class NewsletterManager
{
    private $mailer;

    public function __construct(Mailer $mailer = null)
    {
        $this->mailer = $mailer;
    }

    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getMailer()
    {
        return $this->mailer;
    }
}
