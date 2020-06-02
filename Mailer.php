<?php
/**
 *
 * Created by PhpStorm.
 * User: jacky.yao
 * Date: 2020/6/2
 * Time: 21:19
 */

namespace Mail;

class Mailer
{
    private $transport;

    /**
     * Mailer constructor.
     * @param $transport
     */
    public function __construct($transport)
    {
        $this->transport = $transport;
    }

}