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

namespace App\Entity\Pair;

use App\Entity\User\User;
use App\Repository\Pair\PairRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @author      Andrew SASSOYE <andrew@sassoye.be>
 * @copyright   Copyright (C) 2020 Andrew SASSOYE
 * @license     https://www.gnu.org/licenses/agpl-3.0 AGPL-3.0
 *
 * @ORM\Entity(repositoryClass=PairRepository::class)
 * @ORM\Table(name="pair__pair")
 */
class Pair
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="user1_id", referencedColumnName="id")
     *
     * @Assert\Unique(message="Vous ne pouvez faire qu'une seule demande de binome")
     */
    protected $student1;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="user2_id", referencedColumnName="id")
     */
    protected $student2;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $accepted;

    /**
     * Pair constructor.
     */
    public function __construct()
    {
        $this->accepted = false;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return Pair
     */
    public function setId(?int $id): Pair
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getStudent1(): ?User
    {
        return $this->student1;
    }

    /**
     * @param User|null $student1
     *
     * @return Pair
     */
    public function setStudent1(?User $student1): Pair
    {
        $this->student1 = $student1;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getStudent2(): ?User
    {
        return $this->student2;
    }

    /**
     * @param User|null $student2
     *
     * @return Pair
     */
    public function setStudent2(?User $student2): Pair
    {
        $this->student2 = $student2;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    /**
     * @param bool $accepted
     *
     * @return Pair
     */
    public function setAccepted(bool $accepted): Pair
    {
        $this->accepted = $accepted;
        return $this;
    }
}