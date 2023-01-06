<?php

namespace App\Repository;

use App\Entity\Arrendatario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Arrendatario>
 *
 * @method Arrendatario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arrendatario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arrendatario[]    findAll()
 * @method Arrendatario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArrendatarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arrendatario::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Arrendatario $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Arrendatario $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Arrendatario[] Returns an array of Arrendatario objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Arrendatario
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllChoices(){
        $choices = array();
        foreach($this->findAll() as $arrendatario){
            $choices[strval($arrendatario)]= $arrendatario->getId();
        }
        return $choices;
    }
}
