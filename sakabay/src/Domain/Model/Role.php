<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Domain\Model\Fonction;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 *
 * @ExclusionPolicy("all")
 *
 */
class Role
{
    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_roles"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_roles",
     * "api_groups"
     * })
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_roles"
     * })
     */
    private $code;

    /**
     * @var Fonction[]
     * @Expose
     * @Groups({
     * "api_roles"
     * })
     */
    private $fonctions;

    /**
     * @var Group[]
     */
    private $groups;

    public function __construct()
    {
        $this->fonctions = new ArrayCollection();
        $this->groups = new ArrayCollection();
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
     * @return Collection|Fonction[]
     */
    public function getFonctions(): Collection
    {
        return $this->fonctions;
    }

    /**
     * Get the value of roles
     * @return  Roles[]
     */
    public function addFonction(Fonction $fonction): self
    {
        if (!$this->fonctions->contains($fonction)) {
            $this->fonctions[] = $fonction;
        }

        return $this;
    }

    /**
     * Set the value of fonctions
     * @param  Fonctions[]  $fonctions
     * @return  self
     */
    public function removeFonction(Fonction $fonction): self
    {
        if ($this->fonctions->contains($fonction)) {
            $this->fonctions->removeElement($fonction);
        }
        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addRole($this);
        }
        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeRole($this);
        }
        return $this;
    }
}
