<?php
declare(strict_types=1);
namespace App;
use App\Exceptions\ViewNotFoundException;
use JetBrains\PhpStorm\Pure;


class View
{
    public function __construct(protected string $view, protected array $params=[])
    {
    }

    #[Pure] public static function make(string $view, array $params=[]): static
    {
        return new static($view, $params);
    }

    public function render(): bool|string
    {
        $viewPath=VIEW_PATH . '/' . $this->view . '.php';
        if(! file_exists($viewPath))
        {
            throw new ViewNotFoundException();
        }

        foreach ($this->params as $key=>$value) {
            $$key=$value;
        }

        ob_start();
        include $viewPath;
        return (string) ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }

}