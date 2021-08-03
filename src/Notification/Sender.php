<?php

namespace App\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;



class Sender
{

        protected $mailer;

    /**
     * Sender constructor.
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendNewUserNotificationToAdmin(UserInterface $user)
    {   // pour tester
        //file_put_contents('debug.txt', $user->getEmail());
        $message = new Email();
        $message-> from('yannickroubieu@gmail.com')
            ->to('yannickroubieu@gmail.com')
            ->subject('new account created on series.com!!')
            ->html('<h1>New account ! </h1>email: ' .$user->getEmail());

        $this->mailer->send($message);
    }
}