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

    public function map($sourceInstnce, $destinationClass)
    {
        $destinationInstance = new $destinationClass;
        $reflOfDestination = new \ReflectionObject($destinationInstance);


        $reflOfSource = new \ReflectionObject($sourceInstnce);
        foreach ($reflOfSource->getProperties() as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            if (!$reflOfDestination->hasProperty($sourceProperty->getName()))
                continue;
            $destProperty = $reflOfDestination->getProperty($sourceProperty->getName());
            $destProperty->setAccessible(true);

            $sourceValue = $sourceProperty->getValue($sourceInstnce);
            if ($sourceValue != null)
                $destProperty->setValue($destinationInstance,$sourceValue);
        }

        return $destinationInstance;
    }
}