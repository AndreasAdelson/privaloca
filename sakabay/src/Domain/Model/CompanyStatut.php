<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CompanyStatutRepository::class)
 *
 * @ExclusionPolicy("all")
 *
 */
class CompanyStatut
{
    /**
     * @var int
     * @Expose
     * @Groups({
     * "api_company_statut",
     * "api_admin_companies",
     * "api_companies"
     * })
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_company_statut",
     * "api_admin_companies",
     * "api_companies"
     * })
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @Groups({
     * "api_company_statut",
     * "api_admin_companies",
     * "api_companies",
     * "api_dashboard_utilisateur"
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

    public function __construct()

    {
        $this->companys = new ArrayCollection();
    }

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
     * @return Company[]
     */
    public function getCompanys(): Collection
    {
        return $this->companys;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companys->contains($company)) {
            $this->companys[] = $company;
            $company->setCompanystatut($this);
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
