<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Domain\Model\Category;
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
     * "api_admin_companies"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_categories",
     * "api_companies",
     * "api_admin_companies"
     * })
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_categories"
     * })
     */
    private $code;


    /**
     * @var Company[]
     * @Expose
     * @Groups({
     * })
     */
    private $company;


    public function __construct()

    {
        $this->id = 1;
        $this->company = new ArrayCollection();
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
     * Set the value of id
     * @param  int  $id
     * @return  self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
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
     * Get the value of description
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     * @param  string  $description
     * @return  self
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->fonctions;
    }

    /**
     * Get the value of categorys
     * @return  Categorys[]
     */
    public function addCategory(Category $fonction): self
    {
        if (!$this->fonctions->contains($fonction)) {
            $this->fonctions[] = $fonction;
        }

        return $this;
    }

    /**
     * Set the value of fonctions
     * @param  Categorys[]  $fonctions
     * @return  self
     */
    public function removeCategory(Category $fonction): self
    {
        if ($this->fonctions->contains($fonction)) {
            $this->fonctions->removeElement($fonction);
        }
        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeCategory($this);
        }
        return $this;
    }
    /**
     * @return Collection|Company[]
     */
    public function getCompany(): Collection
    {
        return $this->company;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->company->contains($company)) {
            $this->company[] = $company;
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
}
