<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=BesoinStatutRepository::class)
 *
 * @ExclusionPolicy("all")
 *
 */
class BesoinStatut
{
    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_besoin_statut",
     * "api_admin_companies",
     * "api_companies",
     * "api_besoins"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_besoin_statut",
     * "api_admin_companies",
     * "api_companies",
     * "api_besoins"
     * })
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_besoin_statut",
     * "api_admin_companies",
     * "api_companies",
     * "api_dashboard_utilisateur",
     * "api_besoins"
     * })
     */
    private $code;

    /**
     * @var Besoin[]
     * @Expose
     * @Groups({
     * })
     */
    private $besoins;

    public function __construct()

    {
        $this->besoins = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     * @param  string  $name
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of code
     * @return  string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     * @param  string  $code
     * @return  self
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Besoin[]
     */
    public function getBesoins(): Collection
    {
        return $this->besoins;
    }

    public function addBesoin(Besoin $besoin): self
    {
        if (!$this->besoins->contains($besoin)) {
            $this->besoins[] = $besoin;
            $besoin->setBesoinstatut($this);
        }

        return $this;
    }

    public function removeBesoin(Besoin $besoin): self
    {
        if ($this->besoins->contains($besoin)) {
            $this->besoins->removeElement($besoin);
        }
        return $this;
    }
}
