<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 *
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
     * "api_categories"
     * })
     * @var Categorie
     *
     */
    private $category;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $nom;

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
    private $url_name;
    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $email;
    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $nomCostumer;
    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $lastNameCostumer;
    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_companies"
     * })
     */
    private $imageProfil;


    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set "api_companies"
     *
     * @param  string  $nom  "api_companies"
     *
     * @return  self
     */
    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getNumSiret()
    {
        return $this->numSiret;
    }

    /**
     * Set "api_companies"
     *
     * @param  string  $numSiret  "api_companies"
     *
     * @return  self
     */
    public function setNumSiret(string $numSiret)
    {
        $this->numSiret = $numSiret;

        return $this;
    }

    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getUrl_name()
    {
        return $this->url_name;
    }

    /**
     * Set "api_companies"
     *
     * @param  string  $url_name  "api_companies"
     *
     * @return  self
     */
    public function setUrl_name(string $url_name)
    {
        $this->url_name = $url_name;

        return $this;
    }

    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getImageProfil()
    {
        return $this->imageProfil;
    }

    /**
     * Set "api_companies"
     *
     * @param  string  $imageProfil  "api_companies"
     *
     * @return  self
     */
    public function setImageProfil(string $imageProfil)
    {
        $this->imageProfil = $imageProfil;

        return $this;
    }

    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getNomCostumer()
    {
        return $this->nomCostumer;
    }

    /**
     * Set "api_companies"
     *
     * @param  string  $nomCostumer  "api_companies"
     *
     * @return  self
     */
    public function setNomCostumer(string $nomCostumer)
    {
        $this->nomCostumer = $nomCostumer;

        return $this;
    }

    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getLastNameCostumer()
    {
        return $this->lastNameCostumer;
    }

    /**
     * Set "api_companies"
     *
     * @param  string  $lastNameCostumer  "api_companies"
     *
     * @return  self
     */
    public function setLastNameCostumer(string $lastNameCostumer)
    {
        $this->lastNameCostumer = $lastNameCostumer;

        return $this;
    }

    /**
     * Get "api_companies"
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set "api_companies"
     *
     * @param  string  $email  "api_companies"
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

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
}
