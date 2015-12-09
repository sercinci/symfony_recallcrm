<?php

namespace PairPI\Bundle\RecallcrmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Operatori
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Operatori extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="firstname", type="string", length=50)
     */
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="lastname", type="string", length=50)
     */
    private $lastname;

    /**
     * @var boolean
     * @Assert\NotBlank()
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="assigned_calls", type="integer")
     */
    private $assignedCalls;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Report", mappedBy="operator")
     */
    protected $reports;

    public function __construct()
    {
        parent::__construct();
        $this->reports = new ArrayCollection();
    }

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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Operatori
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Operatori
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Operatori
     */
    public function setStatus($status)
    {
        if ($status == 'attivo'){
            $this->status = true;
        } else{
            $this->status = false;
        }
        
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        if ($this->status == true){
            return "attivo";
        } else{
            return "inattivo";
        }
    }

    /**
     * Set assignedCalls
     *
     * @param integer $assignedCalls
     *
     * @return Operatori
     */
    public function setAssignedCalls($assignedCalls = 0)
    {
        $this->assignedCalls = $assignedCalls;

        return $this;
    }

    /**
     * Get assignedCalls
     *
     * @return integer
     */
    public function getAssignedCalls()
    {
        return $this->assignedCalls;
    }

    /**
     * Add report
     *
     * @param \PairPI\Bundle\RecallcrmBundle\Entity\Report $report
     *
     * @return Operatori
     */
    public function addReport(\PairPI\Bundle\RecallcrmBundle\Entity\Report $report)
    {
        $this->reports[] = $report;

        return $this;
    }

    /**
     * Remove report
     *
     * @param \PairPI\Bundle\RecallcrmBundle\Entity\Report $report
     */
    public function removeReport(\PairPI\Bundle\RecallcrmBundle\Entity\Report $report)
    {
        $this->reports->removeElement($report);
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReports()
    {
        return $this->reports;
    }
}
