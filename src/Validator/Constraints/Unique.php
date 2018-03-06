<?php

namespace Smartie\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class Unique extends Constraint
{
    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var array
     */
    public $fields;

    /**
     * @var string
     */
    public $message;

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
