<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_Attachment;
use \Swift_SmtpTransport;

class SendMail
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
   
    public function sendMail($to, $cci, $object, $html, $attachement, $sender)
    {
        $transport = (new Swift_SmtpTransport('smtp.boa.mg',25));
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message($object);
        $message->setPriority(1);
        $message->setFrom($sender);
        
        $message->setTo($to);
        $message->setBody($html, 'text/html');
        //$message->setBcc($cci);

        foreach ((array) $cci as &$value) 
        {
            $message->addBcc($value);
        }
        unset($value);

        foreach ((array) $attachement as &$value) 
        {
            $message->attach(Swift_Attachment::fromPath($value));
        }
        unset($value);
        
        $mailer->send($message);
    }

    public function validiteAtteintReg($receiver, $ref_credoc, $dateValidite)
    {
        $email = (new Email())
            ->from('bayronkevin1@gmail.com')
            ->to($receiver)
            ->subject('Alerte validité LCI atteinte')
            ->text('Bonjour,
            Je tiens à vous informer que la lettre de crédit irrévocable (LCI) numéro ' . $ref_credoc . ' atteindra sa date de validité le ' . $dateValidite . ', mais qu\'aucune utilisation n\'a encore été enregistrée.
            Si vous avez des questions, veuillez contacter un responsable de la direction des opérations internationales.
            Cordialement,
            DI BOA MADAGASCAR')
            ->html('<p>Bonjour,</p>
            <p>Je tiens à vous informer que la lettre de crédit irrévocable (LCI) numéro ' . $ref_credoc . ' atteindra sa date de validité le ' . $dateValidite . ', mais qu\'aucune utilisation n\'a encore été enregistrée.</p>
            <p>Si vous avez des questions, veuillez contacter un responsable de la direction des opérations internationales.</p>
            <p>Cordialement,<br>
            DI BOA MADAGASCAR</p>');

        $this->mailer->send($email);
    }
    
    public function validiteAtteintDoc($receiver, $ref_credoc, $dateValidite)
    {
        $email = (new Email())
            ->from('bayronkevin1@gmail.com')
            ->to($receiver)
            ->subject('Alerte : Validité du CREDOC atteinte')
            ->text('Bonjour,
            Je tiens à vous informer que le CREDOC numéro ' . $ref_credoc . ' a atteint sa date d\'échéance le ' . $dateValidite . '. À ce jour, certaines utilisations n\'ont pas encore été saisies.
            Si vous avez des questions ou avez besoin de précisions supplémentaires, n\'hésitez pas à contacter un responsable de la direction des opérations internationales.
            Cordialement,
            DI BOA MADAGASCAR')
            ->html('<p>Bonjour,</p>
            <p>Je tiens à vous informer que le CREDOC numéro ' . $ref_credoc . ' a atteint sa date d\'échéance le ' . $dateValidite . '. À ce jour, certaines utilisations n\'ont pas encore été saisies.</p>
            <p>Si vous avez des questions ou avez besoin de précisions supplémentaires, n\'hésitez pas à contacter un responsable de la direction des opérations internationales.</p>
            <p>Cordialement,<br>
            DI BOA MADAGASCAR</p>');
    
        $this->mailer->send($email);
    }
}