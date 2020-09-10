<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Role;
use App\Domain\Model\Utilisateur;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @UniqueEntity("code")
 * @ExclusionPolicy("all")
 */
class Group
{
    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_utilisateurs",
     * "api_groups"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_utilisateurs",
     * "api_groups"
     * })
     */
    private $code;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_utilisateurs",
     * "api_groups"
     * })
     */
    private $name;

    /**
     * @var Role[]
     * @Expose
     * @Groups({
     * "api_groups"
     * })
     */
    private $roles;

    /**
     * @var Utilisateur[]
     * @Expose
     * @Groups({
     * "api_groups"
     * })
     */
    private $utilisateurs;



    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
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
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->addGroup($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->removeElement($utilisateur);
            $utilisateur->removeGroup($this);
        }

        return $this;
    }
}
