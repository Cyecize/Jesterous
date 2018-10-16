<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 11:58 AM
 */

namespace AppBundle\Util;


class ModelMapper
{

    public function map($sourceInstance, $destinationClass)
    {
        return $this->merge($sourceInstance, new $destinationClass);
    }

    public function merge($sourceInstance, $destinationInstance, bool $allowNull = false)
    {
        $reflOfDestination = new \ReflectionObject($destinationInstance);
        $reflOfSource = new \ReflectionObject($sourceInstance);

        foreach ($reflOfSource->getProperties() as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            if (!$reflOfDestination->hasProperty($sourceProperty->getName()))
                continue;
            $destProperty = $reflOfDestination->getProperty($sourceProperty->getName());
            $destProperty->setAccessible(true);

            $sourceValue = $sourceProperty->getValue($sourceInstance);

            if (!$allowNull && $sourceValue == null)
                continue;
            $destProperty->setValue($destinationInstance, $sourceValue);
        }

        return $destinationInstance;
    }
}