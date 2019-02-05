<?php

namespace AppBundle\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateToStringTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if($value == NULL)
        {
            return '';
        }
        if(!$value instanceof \DateTime)
        {
            return $value;
        }
        return $value->format('Y-m-d');
    }

    public function reverseTransform($value)
    {
        return new \DateTime($value);
    }
}