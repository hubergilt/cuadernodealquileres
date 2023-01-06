<?php

namespace App\Repository;

use App\Entity\Deposito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deposito>
 *
 * @method Deposito|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deposito|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deposito[]    findAll()
 * @method Deposito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepositoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deposito::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Deposito $entity, bool $flush = true): void
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
    public function remove(Deposito $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Deposito[] Returns an array of Deposito objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Deposito
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function queryToFindAll(){
        return $this->getEntityManager()
            ->createQuery( dql: '
                SELECT deposito.id, deposito.monto, deposito.anio, deposito.mes, deposito.observacion, deposito.fechaDeposito
                FROM App:Deposito deposito
                ORDER BY deposito.id DESC
            ');
    }

    public function queryToFindAllWithJoins(){
        return $this->getEntityManager()
            ->createQuery( dql: '               
                SELECT deposito.id, deposito.monto, deposito.anio, deposito.mes, deposito.observacion, deposito.fechaDeposito,
                CONCAT(arrendatario.nombres,\' \',arrendatario.paterno,\' \',arrendatario.materno) jarrendatario,
                CONCAT(ambiente.nombre,\' \',ambiente.piso) jambiente
                FROM App:Deposito deposito
                INNER JOIN App:Arrendatario arrendatario WITH deposito.arrendatario=arrendatario.id
                INNER JOIN App:Ambiente ambiente WITH deposito.ambiente=ambiente.id
                ORDER BY deposito.id DESC 
            ');
    }

}
