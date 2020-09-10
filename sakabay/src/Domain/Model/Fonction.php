<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Role;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=FonctionRepository::class)
 * @UniqueEntity("code")
 * @ExclusionPolicy("all")
 */
class Fonction
{
    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_utilisateurs",
     * "api_fonctions",
     * "api_roles",
     * "api_groups"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_utilisateurs",
     * "api_fonctions",
     * "api_roles",
     * "api_groups"
     * })
     */
    private $code;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_utilisateurs",
     * "api_fonctions",
     * "api_roles",
     * "api_groups"
     * })
     */
    private $description;

    /**
     * @var Role[]
     * @Expose
     * @Groups({
     * "api_utilisateurs"
     * })
     */
    private $roles;



    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     * @param  Roles[]  $roles
     * @return  self
     */
    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addFonction($this);
        }

        return $this;
    }


    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeFonction($this);
        }
        return $this;
    }
}
