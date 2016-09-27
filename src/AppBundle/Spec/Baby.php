<?php

declare(strict_types = 1);

namespace AppBundle\Spec;

use RulerZ\Spec\AbstractSpecification;

class Baby extends AbstractSpecification
{
    public function getRule()
    {
        return 'birthDate > age(2)';
    }
}
