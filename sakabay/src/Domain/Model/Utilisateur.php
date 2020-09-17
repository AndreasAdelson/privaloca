<?php

namespace App\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(fields={"login"})
 * @UniqueEntity(fields={"email"})
 * @ExclusionPolicy("all")
 */
class Utilisateur implements UserInterface
{

    const PREFIX_ROLE = 'ROLE_';
    const SERVER_PATH_TO_IMAGE_FOLDER = '../../../sharedFiles';

    /**
     * @var integer
     * @Expose
     * @Groups({
     * "api_utilisateurs"
     * })
     */
    private $id;

    /**
     * @var string|null
     * @Expose
     * @Groups({
     * "api_utilisateurs"
     * })
     */
    private $firstName;


    /**
     * @var string|null
     * @Expose
     * @Groups({
     * })
     */
    private $plainPassword;

    /**
     * @var string|null
     * @Expose
     * @Groups({
     * "api_utilisateurs"
     * })
     */
    private $lastName;

    /**
     * @var string
     * @Assert\Email()
     * @Expose
     * @Groups({
     * "api_utilisateurs"
     * })
     */
    private $email;


    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_utilisateurs",
     * "api_groups"
     * })
     */
    private $login;

    /**
     * @Expose
     * @Groups({
     * })
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Expose
     * @Groups({
     * })
     */
    private $password;

    /**
     * @var Group[]
     * @Expose
     * @Groups({
     * })
     */
    private $groups;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_utilisateurs"
     * })
     */
    private $imageProfil;

    /**
     * Unmapped property to handle file uploads
     */
    private $file;


    /**
     * @var DateTime
     *
     */
    private $updated;


    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $name): self
    {
        $this->firstName = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $name): self
    {
        $this->lastName = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UtilisateurInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UtilisateurInterface
     */
    public function getSalt()
    {
        // not needed when using the "auto" algorithm in security.yaml
    }

    /**
     * @see UtilisateurInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set the value of login
     *
     * @return  self
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get the value of plainPassword
     *
     * @return  string|null
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @param  string|null  $plainPassword
     *
     * @return  self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

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
            $this->addRolesOfGroup($group);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $this->updateRoles();
        }

        return $this;
    }

    /**
     * Return the security roles of the users. Security roles are the user's functions.
     * Return an array of all user's functions in uppercase prefixed by 'ROLE_'. This
     * array does not contain duplication.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $securityRoles = [];

        foreach ($this->getGroups() as $group) {
            foreach ($group->getRoles() as $role) {
                foreach ($role->getFonctions() as $fonction) {
                    $securityRoleName = Utilisateur::PREFIX_ROLE . mb_strtoupper($fonction->getCode());
                    $securityRoles[] = $securityRoleName;
                }
            }
        }
        // if ($this->getDefaultRole()) {
        //     foreach ($this->getDefaultRole()->getFonctions() as $fonction) {
        //         $securityRoleName = Utilisateur::PREFIX_ROLE.mb_strtoupper($fonction->getCode());
        //         $securityRoles[] = $securityRoleName;
        //     }
        // }

        return array_values(array_unique($securityRoles));
    }

    /**
     * Called when a group is added to the user.
     * Add the functions of the group to the user's security roles.
     */
    private function addRolesOfGroup($group): void
    {
        foreach ($group->getRoles() as $role) {
            foreach ($role->getFonctions() as $fonction) {
                $securityRoleName = Utilisateur::PREFIX_ROLE . mb_strtoupper($fonction->getCode());
                if (!in_array($securityRoleName, $this->roles)) {
                    $this->roles[] = $securityRoleName;
                }
            }
        }
    }

    /**
     * Called when a group is deleted from the groups list.
     * Remove the roles from this group which were not in other groups of the user.
     */
    private function updateRoles(): void
    {
        $tmpRoles = [];
        foreach ($this->getGroups() as $group) {
            foreach ($group->getRoles() as $role) {
                foreach ($role->getFonctions() as $fonction) {
                    $securityRoleName = Utilisateur::PREFIX_ROLE . mb_strtoupper($fonction->getCode());
                    $tmpRoles[] = $securityRoleName;
                }
            }
        }
        $this->roles = array_values(array_unique($tmpRoles));
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

    /**
     * Get the value of updated
     *
     * @return  datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     *
     * @param  datetime  $updated
     *
     * @return  self
     */
    public function setUpdated(\DateTimeInterface $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire.
     */
    public function refreshUpdated()
    {
        $this->setUpdated(new \DateTime());
    }
}
