<?php

namespace App\Mailer;

class Mailer
{
    /**
     * @var \CodeIgniter\Email\Email
     */
    private $mailer;

    public function __construct()
    {
        $config = array(
            'protocol'    => getenv('MAILER_PROTOCOL'),
            'SMTPHost'    => getenv('MAILER_HOST'),
            'SMTPPort'    => getenv('MAILER_PORT'),
            'SMTPCrypto'  => getenv('MAILER_CRYPTO'),
            'SMTPUser'    => getenv('MAILER_USER'),
            'SMTPPass'    => getenv('MAILER_PASSWORD'),
            'mailType'    => 'html',
            'wordwrap'    => TRUE,
            'charset'     => 'utf-8',
            'newline'     => "\r\n"
        );

        $this->mailer = \Config\Services::email()->initialize($config);
    }

    /**
     * Send email
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail(string $from, string $to, string $subject, string $body): array
    {
        $this->mailer->setFrom($from);
        $this->mailer->setTo($to);
        $this->mailer->setSubject($subject);
        $this->mailer->setMessage($body);

        try {
            $this->mailer->send();
            $response = ['status' => 1, 'message' => trad('Message has been sent')];
        } catch (\Exception $e) {
            $response = ['status' => 0, 'message' => $e->getMessage()];
        }

        return $response;
    }
}