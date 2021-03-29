<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 * @UniqueEntity(fields={"besoin", "company"})
 * @ExclusionPolicy("all")
 */
class Answer
{
    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $message;

    /**
     * @var DateTime
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $dtCreated;

    /**
     * @var Besoin
     * @Expose
     * @Groups({
     * })
     */
    private $besoin;

    /**
     * @var Company
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $company;



    public function __construct()
    {
    }

    /**
     * Get the value of id
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the value of message
     * @return  string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     * @param  string  $message
     * @return  self
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of dtCreated
     *
     * @return  int
     */
    public function getDtCreated(): ?\DateTimeInterface
    {
        return $this->dtCreated;
    }

    /**
     * Set the value of dtCreated
     * @param  DateTime  $dtCreated
     * @return  self
     */
    public function setDtCreated(\DateTimeInterface $dtCreated)
    {
        $this->dtCreated = $dtCreated;

        return $this;
    }

    /**
     * Get the value of besoin
     *
     * @return  Besoin
     */
    public function getBesoin()
    {
        return $this->besoin;
    }

    /**
     * Set the value of besoin
     * @param  Besoin  $besoin
     * @return  self
     */
    public function setBesoin(Besoin $besoin)
    {
        $this->besoin = $besoin;

        return $this;
    }

    /**
     * Get the company
     *
     * @return  Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set the value of company
     *
     * @param  Company  $company
     *
     * @return  self
     */
    public function setCompany(?Company $company)
    {
        $this->company = $company;
        return $this;
    }
}
