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

namespace App\Form\DataTransformer;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToMatriculateTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($value)
    {
        if (is_string($value)) {
            return $value;
        }

        if (null === $value) {
            return;
        }

        return $value->getUsername();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(array('username' => $value));

        if (null === $user) {
            throw new TransformationFailedException(sprintf(
                'Le matricule "%s" n\'existe pas!',
                $value
            ));
        }

        return $user;
    }
}