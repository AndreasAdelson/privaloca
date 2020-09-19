<?php

namespace App\Domain\Model;

use App\Domain\Model\Utilisateur;
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
     * "api_companies"
     * })
     */
    private $id;

    /**
     * @Expose
     * @Groups({
     * "api_companystatut"
     * })
     * @var CompanyStatut
     *
     */
    private $companystatut;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $name;

    /**
     * @var integer
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $numSiret;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $urlName;

    /**
     * @var Utilisateur
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $utilisateur;

    /**
     * @Expose
     * @Groups({
     * "api_categories",
     * "api_companies"
     * })
     * @var Categorie
     *
     */
    private $category;


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
     *
     * @param  string  $urlName
     *
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
}
