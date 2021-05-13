<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Application\Utils\StringUtils;
use App\Domain\Model\CompanySubscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixCompanySubscription extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
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
                $dtDebut,
                $dtFin,
                $company,
                $subscription,
                $subscriptionStatus
            ) = explode(';', trim($data));

            $companySubscription = new CompanySubscription();
            $companySubscription->setDtDebut(new \DateTime($dtDebut, new \DateTimeZone('Europe/Paris')));
            $companySubscription->setDtFin(new \DateTime($dtFin, new \DateTimeZone('Europe/Paris')));
            $companySubscription->setCompany($this->getReference('company_' . $company));
            $companySubscription->setSubscription($this->getReference('subscription_' . $subscription));
            $companySubscription->setSubscriptionStatus($this->getReference('subscriptionStatus_' . $subscriptionStatus));

            $manager->persist($companySubscription);
            $this->addReference('companySubscription_' . $dtFin, $companySubscription);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}
