<?php
namespace App\Service;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class MailService
{
    private Mailer $mailer;

    public function __construct()
    {
        // 💡 Tu peux mettre ici ton email et ton mot de passe d'application
        $username = 'benghorbelmenyar1@gmail.com';
        $password = 'yzvsxiwgrpjbwrbs';
        
        // Encodage du mot de passe
        $encodedPassword = urlencode($password);
        
        // Construction du DSN avec mot de passe encodé
        $dsn = "smtps://$username:$encodedPassword@smtp.gmail.com:465";
        
        $transport = Transport::fromDsn($dsn);
        $this->mailer = new Mailer($transport);
    }

    public function envoyerMail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('benghorbelmenyar1@gmail.com')
            ->to($to)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}
