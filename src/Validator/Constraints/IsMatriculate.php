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

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsMatriculate
 *
 * @author      Andrew SASSOYE <andrew@sassoye.be>
 * @copyright   Copyright (C) 2020 Andrew SASSOYE
 * @license     https://www.gnu.org/licenses/agpl-3.0 AGPL-3.0
 *
 * @Annotation
 */
class IsMatriculate extends Constraint
{
    public $message = "{{ string }} n'est pas un matricule correcte";

    public function validatedBy()
    {
        return \get_class($this) . 'Validator';
    }
}