<?php

namespace Monolog\Handler;

use Mailgun\Mailgun;
use Monolog\Logger;

/**
 * MailgunHandler uses the Mailgun library to send e-mails
 *
 * @author Matthieu Verrecchia <matthieu.verrecchia@gmail.com>
 */
class MailgunHandler extends MailHandler
{
    /**
     * The Mailgun API Instance
     *
     * @var Mailgun
     */
    protected $mailgunInstance;

    /**
     * The Mailgun domain to use
     *
     * @var string
     */
    protected $mailgunDomain;

    /**
     * The sender email address
     *
     * @var string
     */
    protected $from;

    /**
     * The email destination addresses
     *
     * @var array
     */
    protected $to;

    /**
     * The email subject
     *
     * @var string
     */
    protected $subject;

    /**
     * @param Mailgun      $mailgunInstance The Mailgun API Instance
     * @param string       $mailgunDomain   The Mailgun string domain
     * @param string       $from            Email address to use as sender
     * @param string|array $to              Email address or array of email addresses as recipients
     * @param string       $subject         Subject of the email
     * @param int          $level           The minimum logging level at which this handler will be triggered
     * @param bool         $bubble          Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(Mailgun $mailgunInstance, $mailgunDomain, $from, $to, $subject, $level = Logger::ERROR, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->mailgunInstance  = $mailgunInstance;
        $this->mailgunDomain    = $mailgunDomain;
        $this->to               = is_array($to) ? $to : array($to);
        $this->subject          = $subject;
    }

    /**
     * {@inheritdoc}
     */
    protected function send($content, array $records)
    {
        foreach ($this->to as $recipient) {
            $this->mailgunInstance->sendMessage($this->mailgunDomain, array(
                'from'      => $this->from,
                'to'        => $recipient,
                'subject'   => $this->subject,
                'text'      => $content
            ));
        }
    }
}
