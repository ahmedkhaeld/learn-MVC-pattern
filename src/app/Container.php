<?php
declare(strict_types=1);
namespace App;

use App\Exceptions\Container\ContainerException;
use App\Exceptions\Container\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{
    private array $entries=[];

    public function get(string $id)
    {
        if( $this->has($id)){
            $entry=$this->entries[$id];

            return $entry($this);
        }

        return $this->resolve($id);

    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    /**
     *@param string $id fully qualified class name
     * @param callable $concrete the implementation or the resolver of that class
     *
     */
    public function set(string $id, callable $concrete):void
    {
        $this->entries[$id]=$concrete;

    }


    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function resolve(string $id)
    {
        // 1. Inspect the class that we are trying to get from the container
        $reflectionClass=new ReflectionClass($id);
        if(! $reflectionClass->isInstantiable()){ // check if class is instantiable that is not either an interface or abstract class
            throw new ContainerException('Class "' .$id .'" is not instantiable');
        }

        // 2. Inspect the constructor of the class
        $constructor=$reflectionClass->getConstructor(); // return an object of ReflectionMethod or null if there is no constructor

        if(! $constructor){ // check for the cases where class has no constructor if it hasn't return new instance
            return new $id;
        }
        // 3. Inspect the constructor parameters (dependencies)
        $parameters=$constructor->getParameters();

        if(! $parameters){
            return new $id;
        }
        // 4. if the constructor parameter is a class then try to resolve the class using the container
        $dependencies=array_map(
            function(ReflectionParameter $param) use ($id){
                $name=$param->getName();
                $type=$param->getType();

                if(! $type){
                    throw new ContainerException(
                        'Failed to resolve class"'. $id. '" because '. $name.'"is missing a type hint'
                    );
                }

                if($type instanceof \ReflectionUnionType){
                    throw new ContainerException(
                        'Failed to resolve class"' .$id. '"because union type for param"'. $name. '"'
                    );
                }

                if($type instanceof \ReflectionNamedType && ! $type->isBuiltin()){
                    return $this->get($type->getName());
                }
                throw new ContainerException(
                  'failed to resolve"' .$id. '"because invalid param "' .$name. '"'
                );
            },
            $parameters
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}