<?php
/*
 * Copyright (C) 2020 Andrew SASSOYE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Repository\Pair;

use App\Entity\Pair\Pair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pair::class);
    }

    public function findByStudentId(int $studentId)
    {
        $qb = $this->createQueryBuilder('pair')
            ->where('pair.student1 = :student')
            ->orWhere('pair.student2 = :student')
            ->setParameter('student', $studentId);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function refuseAllExceptOne(int $studentId, int $id)
    {
        $qb = $this->createQueryBuilder('pair')
            ->delete()
            ->where('pair.student2 = :student')
            ->andWhere('pair.id != :id')
            ->setParameter('student', $studentId)
            ->setParameter('id', $id);

        $query = $qb->getQuery();

        return $query->execute();
    }
}