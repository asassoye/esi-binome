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

namespace App\Form\Type;

use App\Form\DataTransformer\UserToMatriculateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PairFormType extends AbstractType
{
    private $transformer;

    public function __construct(UserToMatriculateTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('student', TextType::class, [
                'invalid_message' => 'Ce matricule n\'existe pas ou l\'étudiant ne s\'est pas encore inscrit sur la plateforme.'
            ])
            ->add('submit', SubmitType::class);

        $builder->get('student')
            ->addViewTransformer($this->transformer);
    }
}
