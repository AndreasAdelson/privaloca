<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=SousCategoryRepository::class)
 *
 * @ExclusionPolicy("all")
 *
 */
class SousCategory
{
    /**
     *
     * @var int
     * @Expose
     * @Groups({
     * "api_sous_categories",
     * "api_categories",
     * "api_companies",
     * "api_admin_companies",
     * "api_besoins"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_sous_categories",
     * "api_categories",
     * "api_companies",
     * "api_admin_companies",
     * "api_besoins"
     * })
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_sous_categories",
     * "api_categories",
     * "api_besoins",
     * "api_admin_companies"
     * })
     */
    private $code;


    /**
     * @var Category
     * @Expose
     * @Groups({
     * "api_sous_categories",
     * })
     */
    private $category;


    /**
     * @var Company
     * @Expose
     * @Groups({
     * })
     */
    private $companys;

    /**
     * @var Company
     * @Expose
     * @Groups({
     * })
     */
    private $besoins;


    public function __construct()

    {
        $this->companys = new ArrayCollection();
        $this->besoins = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }


    /**
     * @return Collection|Company[]
     */
    public function getCompanys(): Collection
    {
        return $this->companys;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companys->contains($company)) {
            $this->companys[] = $company;
            $company->addSousCategory($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companys->contains($company)) {
            $this->companys->removeElement($company);
        }

        return $this;
    }

    /**
     * @return Collection|Besoin[]
     */
    public function getBesoins(): Collection
    {
        return $this->besoins;
    }

    public function addBesoin(Besoin $besoin): self
    {
        if (!$this->besoins->contains($besoin)) {
            $this->besoins[] = $besoin;
            $besoin->addSousCategory($this);
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
