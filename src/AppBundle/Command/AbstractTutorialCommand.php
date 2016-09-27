<?php

declare(strict_types = 1);

namespace AppBundle\Command;

use AppBundle\Spec\Operator\ArrayAgeOperator;
use AppBundle\Spec\Operator\DoctrineAgeOperator;
use RulerZ\Compiler\FileCompiler;
use RulerZ\Compiler\Target;
use RulerZ\Parser\HoaParser;
use RulerZ\RulerZ;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractTutorialCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $reflClass = new \ReflectionClass($this);
        $this->setName('step:'.substr(substr($reflClass->getShortName(), 0, -7), 4));
    }

    protected function getRulerZ(): RulerZ
    {
        // compiler
        $compiler = new FileCompiler(
            new HoaParser(),
            $this->getContainer()->getParameter('kernel.cache_dir').'/rulerz'
        );

        // RulerZ engine
        $doctrineVisitor = new Target\Sql\DoctrineQueryBuilderVisitor();
        $doctrineVisitor->setInlineOperator('age', new DoctrineAgeOperator());

        return new RulerZ(
            $compiler, [
                $doctrineVisitor,
                new Target\ArrayVisitor([
                    'age' => new ArrayAgeOperator(),
                ]),
            ]
        );
    }
}