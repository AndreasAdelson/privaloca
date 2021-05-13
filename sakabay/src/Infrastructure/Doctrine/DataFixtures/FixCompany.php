<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Application\Utils\StringUtils;
use App\Domain\Model\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixCompany extends Fixture implements
    OrderedFixtureInterface,
    ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $fileCSV = $this->container->getParameter('app.fixture_csv_dir') . '/' .
            StringUtils::getNameFileCsvForLoad((new \ReflectionClass($this))->getShortName());

        $lines = file($fileCSV);

        foreach ($lines as $data) {
            list(
                $name,
                $numSiret,
                $urlName,
                $category,
                $utilisateur,
                $companyStatut,
                $address,
                $city,
                $sousCategorys,
                $email,
            ) = explode(';', trim($data));


            $company = new Company();
            $company->setName($name);
            $company->setNumSiret($numSiret);
            $company->setUrlName($urlName);
            $company->setCategory($this->getReference('category_' . $category));
            $company->setUtilisateur($this->getReference('utilisateur_' . $utilisateur));
            $company->setCompanystatut($this->getReference('companyStatut_' . $companyStatut));
            $company->setAddress($this->getReference('address_' . $address));
            $company->setCity($this->getReference('city_' . $city));
            $company->setDtCreated(new \DateTime());
            $company->setEmail($email);

            $listSousCategories = explode('|', $sousCategorys);
            foreach ($listSousCategories as $itemSousCategories) {
                if ('' !== $itemSousCategories) {
                    $company->addSousCategory($this->getReference('sousCategory_' . $itemSousCategories));
                }
            }

            $manager->persist($company);
            $this->addReference('company_' . $name, $company);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
