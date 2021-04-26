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
    private $title;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $description;

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
     * @var DateTime
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $dtUpdated;

    /**
     * @var Category
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $category;

    /**
     * @var Utilisateur
     * @Expose
     * @Groups({
     * })
     */
    private $author;

    /**
     * @var BesoinStatut
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $besoinStatut;

    /**
     * @var SousCategory[]
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $sousCategorys;

    /**
     * @var Answer[]
     * @Expose
     * @Groups({
     * "api_besoins",
     * "api_besoins_utilisateur"
     * })
     */
    private $answers;

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
        $this->sousCategorys = new ArrayCollection();
        $this->answers = new ArrayCollection();
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

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setBesoin($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
        }

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
