<?php

namespace Smartie\Validator\Constraints;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UniqueValidator extends ConstraintValidator
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($class, Constraint $constraint)
    {
        if (!$constraint instanceof Unique) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Unique');
        }

        if (!is_array($constraint->fields) && !is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        if (null === $constraint->entityClass) {
            return;
        }

        $fields = (array) $constraint->fields;

        if (0 === count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        $em = $this->registry->getManagerForClass($constraint->entityClass);
        $repository = $em->getRepository($constraint->entityClass);
        foreach ($fields as $field) {
            $fieldValue = $class->{$field};
            $result = $repository->findBy([$field => $fieldValue]);
            if (0 === count($result)) {
                continue;
            }

            $this->context->buildViolation($constraint->message)
                ->atPath($field)
                ->setInvalidValue($fieldValue)
                ->setCode(UniqueEntity::NOT_UNIQUE_ERROR)
                ->setCause($result)
                ->addViolation();
        }
    }
}
