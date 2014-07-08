<?php

namespace Lasso\VmailBundle\Tests\Unit;

use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;
use DateTime;
use ReflectionParameter;

trait AccessorTest
{
    /**
     * @param mixed $object
     * @param array $ignoredMethods
     * @param null  $proxiedClass
     * @param bool  $debug Use this flag to display a list of class methods not covered by this test
     *
     * @SuppressWarnings(PHPMD.ConstructorWithNameAsEnclosingClass)
     *
     * @return void
     */
    protected function accessorTest($object, $ignoredMethods = array(), $proxiedClass = null, $debug = false)
    {
        $class      = ($proxiedClass) ? $proxiedClass : get_class($object);
        $reflector  = new ReflectionClass($class);
        $properties = $reflector->getProperties();

        $calledMethods = array();
        foreach ($properties as $property) {
            $calledMethods = array_merge($calledMethods, $this->callPropertyGettersAndSetters(new ReflectionObject($object), $object, $property, $ignoredMethods));
        }

        if ($debug) {
            $this->printDebugOutput($class, $calledMethods);
        }
    }

    /**
     * @param object $owner
     * @param object $collectionObject
     * @param string $methodSuffix
     * @param string $methodSuffixPlural
     */
    protected function checkCollectionTest($owner, $collectionObject, $methodSuffix, $methodSuffixPlural = '')
    {
        $itemClass          = get_class($collectionObject);
        $methodSuffix       = ucfirst($methodSuffix);
        $methodSuffixPlural = ($methodSuffixPlural) ? $methodSuffixPlural : $methodSuffix . 's';

        $owner->{"add{$methodSuffix}"}($collectionObject);
        $collection = $owner->{"get{$methodSuffixPlural}"}();
        $this->assertInstanceOf($itemClass, $collection[0]);

        $owner->{"add{$methodSuffix}"}($collectionObject);
        $this->assertCount(2, $owner->{"get{$methodSuffixPlural}"}());

        $owner->{"remove{$methodSuffix}"}($collection[0]);
        $this->assertCount(1, $owner->{"get{$methodSuffixPlural}"}());
    }

    /**
     * @param ReflectionObject   $reflector
     * @param object             $object
     * @param ReflectionProperty $property
     * @param array              $ignoredMethods
     *
     * @return array
     */
    private function callPropertyGettersAndSetters(ReflectionObject $reflector, $object, ReflectionProperty $property, $ignoredMethods)
    {
        $calledMethods = array();
        $calledSet     = false;
        $calledGet     = false;
        $setterValue   = null;
        $getValue      = null;
        $setMethod     = "set" . ucfirst($property->getName());
        $getMethod     = "get" . ucfirst($property->getName());

        if ($this->checkMethod($reflector->getName(), $setMethod, $ignoredMethods)) {
            $calledSet       = true;
            $calledMethods[] = $setMethod;
            $setMethodName   = "set" . ucfirst($property->getName());
            $setterValue     = $this->getSetterValue($reflector, $setMethodName);
            $setValue        = $object->{$setMethodName}($setterValue);
            if (!is_null($setValue)) {
                $this->assertInstanceOf($reflector->getName(), $setValue);
            }
        }
        if ($this->checkMethod($reflector->getName(), $getMethod, $ignoredMethods)) {
            $calledGet       = true;
            $calledMethods[] = $getMethod;
            $getValue        = $object->{"get" . ucfirst($property->getName())}();
        }
        if ($calledSet && $calledGet) {
            $this->assertEquals($setterValue, $getValue);
        }

        return $calledMethods;
    }

    /**
     * @param ReflectionObject $reflector
     * @param string           $methodName
     *
     * @return DateTime|int
     */
    private function getSetterValue(ReflectionObject $reflector, $methodName)
    {
        $value      = 1;
        /** @var ReflectionParameter[] $parameters */
        $parameters = $reflector->getMethod($methodName)->getParameters();
        if ($parameters[0]->getClass()) {
            if ($parameters[0]->getClass()->name == 'DateTime') {
                $value = new DateTime('2000-01-01 00:00:00');
            } else {
                /** @var ReflectionClass $class */
                $class = $parameters[0]->getClass();
                if ($class->isInstantiable()) {
                    try {
                        $value = $class->newInstance();
                    } catch (ReflectionException $e) {

                    }
                }
            }
        }

        return $value;
    }

    /**
     * @param string $class
     * @param array  $calledMethods
     */
    private function printDebugOutput($class, $calledMethods)
    {
        echo "Class: {$class}\n";
        foreach (get_class_methods($class) as $method) {
            if (!in_array($method, $calledMethods)) {
                echo "  " . $method . "\n";
            }
        }
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $ignoredMethods
     *
     * @return bool
     */
    private function checkMethod($class, $method, $ignoredMethods)
    {
        return method_exists($class, $method) && !in_array($method, $ignoredMethods);
    }
}
