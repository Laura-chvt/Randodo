<?php

namespace App\Repository;

use App\Entity\Hike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
* @extends ServiceEntityRepository<Hike>
*/
class HikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hike::class);
    }

    /**
    * recherche de la randonnée par mot clé dans le titre de la randonnée et la localisation
    */
    public function search(string $search): array
    {
        $queryBuilder = $this->createQueryBuilder('h')
            ->leftJoin('h.location', 'l')
            ->addSelect('l')
            ->orderBy('h.name', 'ASC');

        if ($search) {
            $searchSegments = explode(' ', $search);

            for ($i = 0; $i < count($searchSegments); $i++) {
                $NewSearchSegments = trim($searchSegments[$i]);
                if ($NewSearchSegments !== '') {
                    $queryBuilder->andWhere("LOWER(h.name) LIKE LOWER(:valeur_$i) OR LOWER(l.name) LIKE LOWER(:valeur_$i)")
                                 ->setParameter("valeur_$i", '%' . $NewSearchSegments . '%');
                }
            }
        }
        return $queryBuilder->getQuery()->getResult();
    }
}
