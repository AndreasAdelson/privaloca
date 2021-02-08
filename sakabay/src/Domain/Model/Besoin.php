<?php

namespace App\Domain\Model;

use App\Domain\Model\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=BesoinRepository::class)
 *
 * @ExclusionPolicy("all")
 *
 */
class Besoin
{
    /**
     *
     * @var int
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $title;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $description;

    /**
     * @var DateTime
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $dtCreated;


    /**
     * @var DateTime
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $dtUpdated;

    /**
     * @var Category
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $category;

    /**
     * @var Utilisateur
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $author;

    /**
     * @var BesoinStatut
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $besoinStatut;

    /**
     * @var SousCategory[]
     * @Expose
     * @Groups({
     * "api_besoins"
     * })
     */
    private $sousCategorys;


    public function __construct()

    {
        $this->sousCategorys = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }


    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }


    public function getDtCreated(): ?\DateTimeInterface
    {
        return $this->dtCreated;
    }

    public function setDtCreated(\DateTimeInterface $dtCreated)
    {
        $this->dtCreated = $dtCreated;

        return $this;
    }

    public function getDtUpdated(): ?\DateTimeInterface
    {
        return $this->dtUpdated;
    }

    public function setDtUpdated(\DateTimeInterface $dtUpdated)
    {
        $this->dtUpdated = $dtUpdated;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getAuthor(): ?Utilisateur
    {
        return $this->author;
    }

    public function setAuthor(?Utilisateur $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getBesoinStatut(): ?BesoinStatut
    {
        return $this->besoinStatut;
    }

    public function setBesoinStatut(?BesoinStatut $besoinStatut): self
    {
        $this->besoinStatut = $besoinStatut;
        return $this;
    }


    public function getSousCategorys(): Collection
    {
        return $this->sousCategorys;
    }

    public function addSousCategory(SousCategory $sousCategory): self
    {
        if (
            !$this->sousCategorys->contains($sousCategory)
            && $sousCategory->getCategory()->getId() === $this->getCategory()->getId()
        ) {
            $this->sousCategorys[] = $sousCategory;
            $sousCategory->addBesoin($this);
        }

        return $this;
    }

    public function removeSousCategory(SousCategory $sousCategory): self
    {
        if ($this->sousCategorys->contains($sousCategory)) {
            $this->sousCategorys->removeElement($sousCategory);
            $sousCategory->removeBesoin($this);
        }

        return $this;
    }
}
