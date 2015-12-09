<?php

namespace PairPI\Bundle\RecallcrmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Report
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_time", type="datetime")
     */
    private $dateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="reply", type="string", length=50)
     */
    private $reply;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Operatori", inversedBy="reports")
     * @ORM\JoinColumn(name="operator_id", referencedColumnName="id")
     */
    protected $operator;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign", type="string", length=50)
     */
    private $campaign;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="positive_comment", type="string", length=255, nullable=true)
     */
    private $positiveComment;

    /**
     * @var string
     *
     * @ORM\Column(name="negative_comment", type="string", length=255, nullable=true)
     */
    private $negativeComment;

    /**
     * @var text
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Report
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set reply
     *
     * @param string $reply
     *
     * @return Report
     */
    public function setReply($reply)
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * Get reply
     *
     * @return string
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Report
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set campaign
     *
     * @param string $campaign
     *
     * @return Report
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Report
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Report
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Report
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set operator
     *
     * @param \PairPI\Bundle\RecallcrmBundle\Entity\Operatori $operator
     *
     * @return Report
     */
    public function setOperator(\PairPI\Bundle\RecallcrmBundle\Entity\Operatori $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \PairPI\Bundle\RecallcrmBundle\Entity\Operatori
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set positivComment
     *
     * @param string $positiveComment
     *
     * @return Report
     */
    public function setPositiveComment($positiveComment)
    {
        $this->positiveComment = $positiveComment;

        return $this;
    }

    /**
     * Get positiveComment
     *
     * @return string
     */
    public function getPositiveComment()
    {
        return $this->positiveComment;
    }

    /**
     * Set negativeComment
     *
     * @param string $negativeComment
     *
     * @return Report
     */
    public function setNegativeComment($negativeComment)
    {
        $this->negativeComment = $negativeComment;

        return $this;
    }

    /**
     * Get negativeComment
     *
     * @return string
     */
    public function getNegativeComment()
    {
        return $this->negativeComment;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Report
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
