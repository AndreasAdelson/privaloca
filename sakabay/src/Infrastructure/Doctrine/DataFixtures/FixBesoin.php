<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Application\Utils\StringUtils;
use App\Domain\Model\Besoin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FixBesoin extends Fixture implements
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
                $title,
                $description,
                $dtCreated,
                $author,
                $besoinStatut,
                $category,
                $sousCategorys
            ) = explode(';', trim($data));

            $besoin = new Besoin();
            $besoin->setTitle($title);
            $besoin->setDescription($description);
            $besoin->setDtCreated(new \DateTime($dtCreated, new \DateTimeZone('Europe/Paris')));
            $besoin->setDtUpdated(new \DateTime($dtCreated, new \DateTimeZone('Europe/Paris')));
            $besoin->setCategory($this->getReference('category_' . $category));
            $besoin->setAuthor($this->getReference('utilisateur_' . $author));
            $besoin->setBesoinStatut($this->getReference('besoinStatut_' . $besoinStatut));
            $listSousCategorys = explode('|', $sousCategorys);
            foreach ($listSousCategorys as $itemSousCategory) {
                if ('' !== $itemSousCategory) {
                    $besoin->addSousCategory($this->getReference('sousCategory_' . $itemSousCategory));
                }
            }
            $manager->persist($besoin);
            $this->addReference('besoin_' . $title, $besoin);
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
        return 5;
    }
}
