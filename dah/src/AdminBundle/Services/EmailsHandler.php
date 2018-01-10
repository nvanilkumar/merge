<?php

namespace AdminBundle\Services;

class EmailsHandler {

    protected $mailer;

    public function __construct(\Swift_Mailer $mailer, $doctrine) {
        $this->from = "upendrakumar@cestechservices.com";
        $this->mailer = $mailer;
        $this->doctrine = $doctrine;
    }

    public function sendEmail($to, $subject = '', $body, $attached = '') {
        $repository = $this->doctrine->getRepository('AdminBundle:DahUnsubscribe');
        $restrict = $repository->findBy(
                array('email' => $to)
        );

        //$restrict = '';
        if (empty($restrict)) {

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom($this->from)
                    ->setTo($to)
                    ->setBody($body, 'text/html');
            if ($attached != '') {
                $message->attach(\Swift_Attachment::fromPath($attached));
            }

            return $this->mailer->send($message);

            //echo $message;
            //exit;
            // To send HTML mail, the Content-type header must be set
            $headers = "MIME-Version: 1.0" . "\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\n";
            $headers .= 'From: CTS <' . $this->from . '>' . "\r\n";
            //@mail($to, $subject, $body, $headers);
        } else {
            return 0;
        }
    }

}
