<?php

namespace App\Domain\Model;

use App\Domain\Model\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Une entreprise existe déjà avec ce nom"
 * )
 * @ExclusionPolicy("all")
 *
 */
class Company
{
    const PREFIX_ROLE = 'ROLE_';
    const SERVER_PATH_TO_IMAGE_FOLDER = '../../../sharedFiles';

    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies",
     * "api_utilisateurs",
     * "api_dashboard_utilisateur"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies",
     * "api_utilisateurs",
     * "api_dashboard_utilisateur"
     * })
     */
    private $name;

    /**
     * @var integer
     * @Expose
     * @Groups({
     * "api_admin_companies",
     * })
     */
    private $numSiret;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies",
     * "api_dashboard_utilisateur"
     * })
     */
    private $urlName;

    /**
     * @var Utilisateur
     * @Expose
     * @Groups({
     * "api_admin_companies"
     * })
     */
    private $utilisateur;

    /**
     * @Expose
     * @Groups({
     * "api_categories",
     * "api_admin_companies",
     * "api_companies"
     * })
     * @var Categorie
     *
     */
    private $category;

    /**
     * @Expose
     * @Groups({
     * "api_company_statut",
     * "api_admin_companies",
     * "api_dashboard_utilisateur"
     * })
     * @var CompanyStatut
     *
     */
    private $companystatut;

    /**
     * @var Address
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies"
     * })
     */
    private $address;

    /**
     * @var City
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies"
     * })
     */
    private $city;

    /**
     * @var CompanySubscription[]
     * @Expose
     * @Groups({
     * "api_admin_companies",
     * "api_companies",
     * "api_dashboard_utilisateur"
     * })
     */
    private $companySubscriptions;

    /**
     * @var DateTime
     * @Expose
     * @Groups({
     * "api_admin_companies",
     * "api_dashboard_utilisateur"
     * })
     */
    private $dtCreated;

    /**
     * @var String
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies",
     * })
     */
    private $descriptionFull;

    /**
     * @var String
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies",
     * })
     */
    private $descriptionClean;

    /**
     * @var String
     * @Expose
     * @Groups({
     * "api_companies",
     * "api_admin_companies"
     * })
     */
    private $sousCategorys;



    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies",
     * })
     */
    private $imageProfil;

    /**
     * Unmapped property to handle file uploads
     */
    private $file;



    public function __construct()
    {
        $this->companySubscriptions = new ArrayCollection();
        $this->sousCategorys =  new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return  string
     */
    public function getNumSiret()
    {
        return $this->numSiret;
    }

    /**
     *
     * @param  string  $numSiret
     *
     * @return  self
     */
    public function setNumSiret(string $numSiret)
    {
        $this->numSiret = $numSiret;

        return $this;
    }

    /**
     *
     * @return  string
     */
    public function getUrlName()
    {
        return $this->urlName;
    }

    /**
     * @param  string  $urlName
     * @return  self
     */
    public function setUrlName(string $urlName)
    {
        $this->urlName = $urlName;
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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     *
     * @return  CompanyStatut
     */
    public function getCompanystatut()
    {
        return $this->companystatut;
    }

    /**
     *
     * @param  CompanyStatut
     *
     * @return  self
     */
    public function setCompanystatut(CompanyStatut $companystatut)
    {
        $this->companystatut = $companystatut;
        return $this;
    }

    /**
     * Get the value of address
     * @return  Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     * @param  Address  $address
     * @return  self
     */
    public function setAddress(?Address $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     *
     * @return  City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @param  City
     *
     * @return  self
     */
    public function setCity(City $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return Collection|CompanySubscription[]
     */
    public function getCompanySubscriptions(): Collection
    {
        return $this->companySubscriptions;
    }

    public function addCompanySubscription(CompanySubscription $companySubscription): self
    {
        if (!$this->companySubscriptions->contains($companySubscription)) {
            $this->companySubscriptions[] = $companySubscription;
            $companySubscription->setCompany($this);
        }

        return $this;
    }

    public function removeCompanySubscription(CompanySubscription $companySubscription): self
    {
        if ($this->companySubscriptions->contains($companySubscription)) {
            $this->companySubscriptions->removeElement($companySubscription);
        }

        return $this;
    }

    public function getDtCreated(): ?\DateTimeInterface
    {
        return $this->dtCreated;
    }

    public function setDtCreated(\DateTimeInterface $dtCreated): self
    {
        $this->dtCreated = $dtCreated;

        return $this;
    }

    /**
     *
     * @return  string
     */
    public function getDescriptionFull()
    {
        return $this->descriptionFull;
    }

    /**
     * @param  string  $descriptionFull
     * @return  self
     */
    public function setDescriptionFull(string $descriptionFull)
    {
        $this->descriptionFull = $descriptionFull;
        return $this;
    }

    /**
     *
     * @return  string
     */
    public function getDescriptionClean()
    {
        return $this->descriptionClean;
    }

    /**
     * @param  string  $descriptionClean
     * @return  self
     */
    public function setDescriptionClean(string $descriptionClean)
    {
        $this->descriptionClean = $descriptionClean;
        return $this;
    }

    /**
     * @return Collection|SousCategory[]
     */
    public function getSousCategorys(): Collection
    {
        return $this->sousCategorys;
    }

    /**
     * Set the value of sousCategorys
     * @param  SousCategorys[]  $sousCategorys
     * @return  self
     */
    public function addSousCategory(SousCategory $sousCategory): self
    {
        if (
            !$this->sousCategorys->contains($sousCategory)
            && $sousCategory->getCategory()->getId() === $this->getCategory()->getId()
        ) {
            $this->sousCategorys[] = $sousCategory;
            $sousCategory->addCompany($this);
        }

        return $this;
    }


    public function removeSousCategory(SousCategory $sousCategory): self
    {
        if ($this->sousCategorys->contains($sousCategory)) {
            $this->sousCategorys->removeElement($sousCategory);
            $sousCategory->removeCompany($this);
        }
        return $this;
    }

    /**
     * @return  string
     */
    public function getImageProfil()
    {
        return $this->imageProfil;
    }

    /**
     * @param  string  $imageProfil
     *
     * @return  self
     */
    public function setImageProfil(?string $imageProfil)
    {
        $this->imageProfil = $imageProfil;
        return $this;
    }
}
