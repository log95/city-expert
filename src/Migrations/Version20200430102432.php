<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\PointsType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class Version20200430102432 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        /** @var ObjectManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $pointsTypes = [
            (new PointsType())->setName('registration'),
            (new PointsType())->setName('correct_answer'),
            (new PointsType())->setName('show_answer'),
            (new PointsType())->setName('show_hint'),
        ];

        foreach ($pointsTypes as $pointsType) {
            $em->persist($pointsType);
        }

        $em->flush();
    }

    public function down(Schema $schema) : void
    {
        $pointsTypes = [
            'registration',
            'correct_answer',
            'show_answer',
            'show_hint',
        ];

        /** @var ObjectManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $repository = $em->getRepository(PointsType::class);

        $criteria = Criteria::create()
            ->where(Criteria::expr()->in('name', $pointsTypes));

        $pointsTypesObjects = $repository->matching($criteria);
        foreach ($pointsTypesObjects as $pointsTypesObject) {
            $em->remove($pointsTypesObject);
        }

        $em->flush();
    }
}
