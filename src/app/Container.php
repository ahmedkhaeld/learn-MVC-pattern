<?php
declare(strict_types=1);
namespace App;

use App\Exceptions\Container\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    private array $entries=[];

    public function get(string $id)
    {
        if(! $this->has($id)){
            throw new NotFoundException('class "'. $id . '"has no binding');
        }

        $entry=$this->entries[$id];

        return $entry($this);
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
}