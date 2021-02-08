<?php

namespace App\Domain\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CompanySubscriptionRepository::class)
 * @ExclusionPolicy("all")
 */
class CompanySubscription
{
    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies",
     * })
     */
    private $id;

    /**
     * @var DateTime
     * @Expose
     * @Groups({
     * "api_dashboard_utilisateur",
     * "api_companies",
     * "api_admin_companies",
     * })
     */
    private $dtDebut;

    /**
     * @var DateTime
     * @Expose
     * @Groups({
     * "api_dashboard_utilisateur",
     * "api_companies",
     * "api_admin_companies",
     * })
     */
    private $dtFin;

    /**
     * @var Subscription
     * @Expose
     * @Groups({
     * "api_dashboard_utilisateur",
     * "api_companies",
     * })
     */
    private $subscription;

    /**
     * @var Company
     * @Expose
     * @Groups({
     * "api_dashboard_utilisateur",
     * "api_companies",
     * })
     */
    private $company;


    public function __construct()

    {
    }

    /**
     * Get })
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set })
     *
     * @param  int  $id  })
     *
     * @return  self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get })
     *
     * @return  DateTime
     */
    public function getDtDebut()
    {
        return $this->dtDebut;
    }

    /**
     * Set })
     *
     * @param  DateTime  $dtDebut  })
     *
     * @return  self
     */
    public function setDtDebut(DateTime $dtDebut)
    {
        $this->dtDebut = $dtDebut;

        return $this;
    }

    /**
     * Get })
     *
     * @return  DateTime
     */
    public function getDtFin()
    {
        return $this->dtFin;
    }

    /**
     * Set })
     *
     * @param  DateTime  $dtFin  })
     *
     * @return  self
     */
    public function setDtFin(DateTime $dtFin)
    {
        $this->dtFin = $dtFin;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;
        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;
        return $this;
    }
}
