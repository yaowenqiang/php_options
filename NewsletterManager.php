<?php
/**
 *
 * Created by PhpStorm.
 * User: jacky.yao
 * Date: 2020/6/2
 * Time: 21:41
 */

class NewsletterManager
{
    /**
     * @var Mailer $mailer
     */
    private  $mailer;

    /**
     * NewsletterManager constructor.
     * @param $mailer
     */
    public function __construct(\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param Mailer $mailer
     */
    public function setMailer(\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }



}