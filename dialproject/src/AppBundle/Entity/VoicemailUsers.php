<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VoicemailUsers
 *
 * @ORM\Table(name="voicemail_users", indexes={@ORM\Index(name="mailbox_context", columns={"mailbox", "context"})})
 * @ORM\Entity
 */
class VoicemailUsers
{
    /**
     * @var string
     *
     * @ORM\Column(name="customer_id", type="string", length=11, nullable=false)
     */
    private $customerId;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=50, nullable=false)
     */
    private $context;

    /**
     * @var string
     *
     * @ORM\Column(name="mailbox", type="string", length=11, nullable=false)
     */
    private $mailbox;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=5, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=150, nullable=false)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="pager", type="string", length=50, nullable=false)
     */
    private $pager;

    /**
     * @var string
     *
     * @ORM\Column(name="tz", type="string", length=10, nullable=false)
     */
    private $tz;

    /**
     * @var string
     *
     * @ORM\Column(name="attach", type="string", length=4, nullable=false)
     */
    private $attach;

    /**
     * @var string
     *
     * @ORM\Column(name="saycid", type="string", length=4, nullable=false)
     */
    private $saycid;

    /**
     * @var string
     *
     * @ORM\Column(name="dialout", type="string", length=10, nullable=false)
     */
    private $dialout;

    /**
     * @var string
     *
     * @ORM\Column(name="callback", type="string", length=10, nullable=false)
     */
    private $callback;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="string", length=4, nullable=false)
     */
    private $review;

    /**
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=4, nullable=false)
     */
    private $operator;

    /**
     * @var string
     *
     * @ORM\Column(name="envelope", type="string", length=4, nullable=false)
     */
    private $envelope;

    /**
     * @var string
     *
     * @ORM\Column(name="sayduration", type="string", length=4, nullable=false)
     */
    private $sayduration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="saydurationm", type="boolean", nullable=false)
     */
    private $saydurationm;

    /**
     * @var string
     *
     * @ORM\Column(name="sendvoicemail", type="string", length=4, nullable=false)
     */
    private $sendvoicemail;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="string", length=4, nullable=false)
     */
    private $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="nextaftercmd", type="string", length=4, nullable=false)
     */
    private $nextaftercmd;

    /**
     * @var string
     *
     * @ORM\Column(name="forcename", type="string", length=4, nullable=false)
     */
    private $forcename;

    /**
     * @var string
     *
     * @ORM\Column(name="forcegreetings", type="string", length=4, nullable=false)
     */
    private $forcegreetings;

    /**
     * @var string
     *
     * @ORM\Column(name="hidefromdir", type="string", length=4, nullable=false)
     */
    private $hidefromdir;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stamp", type="datetime", nullable=false)
     */
    private $stamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="uniqueid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uniqueid;



    /**
     * Set customerId
     *
     * @param string $customerId
     * @return VoicemailUsers
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string 
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return VoicemailUsers
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set mailbox
     *
     * @param string $mailbox
     * @return VoicemailUsers
     */
    public function setMailbox($mailbox)
    {
        $this->mailbox = $mailbox;

        return $this;
    }

    /**
     * Get mailbox
     *
     * @return string 
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return VoicemailUsers
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return VoicemailUsers
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return VoicemailUsers
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pager
     *
     * @param string $pager
     * @return VoicemailUsers
     */
    public function setPager($pager)
    {
        $this->pager = $pager;

        return $this;
    }

    /**
     * Get pager
     *
     * @return string 
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * Set tz
     *
     * @param string $tz
     * @return VoicemailUsers
     */
    public function setTz($tz)
    {
        $this->tz = $tz;

        return $this;
    }

    /**
     * Get tz
     *
     * @return string 
     */
    public function getTz()
    {
        return $this->tz;
    }

    /**
     * Set attach
     *
     * @param string $attach
     * @return VoicemailUsers
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;

        return $this;
    }

    /**
     * Get attach
     *
     * @return string 
     */
    public function getAttach()
    {
        return $this->attach;
    }

    /**
     * Set saycid
     *
     * @param string $saycid
     * @return VoicemailUsers
     */
    public function setSaycid($saycid)
    {
        $this->saycid = $saycid;

        return $this;
    }

    /**
     * Get saycid
     *
     * @return string 
     */
    public function getSaycid()
    {
        return $this->saycid;
    }

    /**
     * Set dialout
     *
     * @param string $dialout
     * @return VoicemailUsers
     */
    public function setDialout($dialout)
    {
        $this->dialout = $dialout;

        return $this;
    }

    /**
     * Get dialout
     *
     * @return string 
     */
    public function getDialout()
    {
        return $this->dialout;
    }

    /**
     * Set callback
     *
     * @param string $callback
     * @return VoicemailUsers
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get callback
     *
     * @return string 
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Set review
     *
     * @param string $review
     * @return VoicemailUsers
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set operator
     *
     * @param string $operator
     * @return VoicemailUsers
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string 
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set envelope
     *
     * @param string $envelope
     * @return VoicemailUsers
     */
    public function setEnvelope($envelope)
    {
        $this->envelope = $envelope;

        return $this;
    }

    /**
     * Get envelope
     *
     * @return string 
     */
    public function getEnvelope()
    {
        return $this->envelope;
    }

    /**
     * Set sayduration
     *
     * @param string $sayduration
     * @return VoicemailUsers
     */
    public function setSayduration($sayduration)
    {
        $this->sayduration = $sayduration;

        return $this;
    }

    /**
     * Get sayduration
     *
     * @return string 
     */
    public function getSayduration()
    {
        return $this->sayduration;
    }

    /**
     * Set saydurationm
     *
     * @param boolean $saydurationm
     * @return VoicemailUsers
     */
    public function setSaydurationm($saydurationm)
    {
        $this->saydurationm = $saydurationm;

        return $this;
    }

    /**
     * Get saydurationm
     *
     * @return boolean 
     */
    public function getSaydurationm()
    {
        return $this->saydurationm;
    }

    /**
     * Set sendvoicemail
     *
     * @param string $sendvoicemail
     * @return VoicemailUsers
     */
    public function setSendvoicemail($sendvoicemail)
    {
        $this->sendvoicemail = $sendvoicemail;

        return $this;
    }

    /**
     * Get sendvoicemail
     *
     * @return string 
     */
    public function getSendvoicemail()
    {
        return $this->sendvoicemail;
    }

    /**
     * Set deleted
     *
     * @param string $deleted
     * @return VoicemailUsers
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return string 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set nextaftercmd
     *
     * @param string $nextaftercmd
     * @return VoicemailUsers
     */
    public function setNextaftercmd($nextaftercmd)
    {
        $this->nextaftercmd = $nextaftercmd;

        return $this;
    }

    /**
     * Get nextaftercmd
     *
     * @return string 
     */
    public function getNextaftercmd()
    {
        return $this->nextaftercmd;
    }

    /**
     * Set forcename
     *
     * @param string $forcename
     * @return VoicemailUsers
     */
    public function setForcename($forcename)
    {
        $this->forcename = $forcename;

        return $this;
    }

    /**
     * Get forcename
     *
     * @return string 
     */
    public function getForcename()
    {
        return $this->forcename;
    }

    /**
     * Set forcegreetings
     *
     * @param string $forcegreetings
     * @return VoicemailUsers
     */
    public function setForcegreetings($forcegreetings)
    {
        $this->forcegreetings = $forcegreetings;

        return $this;
    }

    /**
     * Get forcegreetings
     *
     * @return string 
     */
    public function getForcegreetings()
    {
        return $this->forcegreetings;
    }

    /**
     * Set hidefromdir
     *
     * @param string $hidefromdir
     * @return VoicemailUsers
     */
    public function setHidefromdir($hidefromdir)
    {
        $this->hidefromdir = $hidefromdir;

        return $this;
    }

    /**
     * Get hidefromdir
     *
     * @return string 
     */
    public function getHidefromdir()
    {
        return $this->hidefromdir;
    }

    /**
     * Set stamp
     *
     * @param \DateTime $stamp
     * @return VoicemailUsers
     */
    public function setStamp($stamp)
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get stamp
     *
     * @return \DateTime 
     */
    public function getStamp()
    {
        return $this->stamp;
    }

    /**
     * Get uniqueid
     *
     * @return integer 
     */
    public function getUniqueid()
    {
        return $this->uniqueid;
    }
}
