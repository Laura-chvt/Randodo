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

    /**
     * Récupère les statistiques globales des randonnées via SQL natif
     */
    public function getHikesStatistics(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT 
                h.hike_name AS hike_name,
                COUNT(DISTINCT hd.id) AS done_count,
                COUNT(DISTINCT uh.hike_user_id) AS fav_count
            FROM hike h
            LEFT JOIN hike_done hd ON h.hike_id = hd.hikesdone_id
            LEFT JOIN user_hike uh ON h.hike_id = uh.user_hike
            GROUP BY h.hike_id, h.hike_name
            ORDER BY done_count DESC, fav_count DESC
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }
}
