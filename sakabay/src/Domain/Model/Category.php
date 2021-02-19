<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 *
 * @ExclusionPolicy("all")
 *
 */
class Category
{
    /**
     *
     * @var int
     * @Expose
     * @Groups({
     * "api_categories",
     * "api_companies",
     * "api_admin_companies",
     * "api_sous_categories",
     * "api_besoins"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_categories",
     * "api_companies",
     * "api_admin_companies",
     * "api_sous_categories",
     * "api_besoins"
     * })
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_categories",
     * "api_sous_categories",
     * "api_besoins",
     * "api_admin_companies"
     * })
     */
    private $code;


    /**
     * @var Company[]
     * @Expose
     * @Groups({
     * })
     */
    private $companys;

    /**
     * @var SousCategory[]
     * @Expose
     * @Groups({
     * "api_categories",
     * "api_besoins"
     * })
     */
    private $sousCategorys;

    /**
     * @var Besoin[]
     * @Expose
     * @Groups({
     * })
     */
    private $besoins;


    public function __construct()

    {
        $this->companys = new ArrayCollection();
        $this->besoins = new ArrayCollection();
        $this->sousCategorys = new ArrayCollection();
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
            $company->setCategory($this);
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
     * @return Collection|SousCategory[]
     */
    public function getSousCategorys(): Collection
    {
        return $this->sousCategorys;
    }

    public function addSousCategory(SousCategory $sousCategory): self
    {
        if (!$this->sousCategorys->contains($sousCategory)) {
            $this->sousCategorys[] = $sousCategory;
            $sousCategory->setCategory($this);
        }

        return $this;
    }

    public function removeSousCategory(SousCategory $sousCategory): self
    {
        if ($this->sousCategorys->contains($sousCategory)) {
            $this->sousCategorys->removeElement($sousCategory);
        }
        $sousCategory->setCategory(null);

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
            $besoin->setCategory($this);
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
