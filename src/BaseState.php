<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\CommentTrait;
use BeastBytes\Mermaid\IdTrait;
use BeastBytes\Mermaid\StyleClassTrait;

abstract class BaseState implements StateInterface
{
    use CommentTrait;
    use IdTrait;
    use StyleClassTrait;

    private const string STATE = '%sstate %s%s <<%s>>';

    public function __construct(?string $id)
    {
        $this->id = $id;
    }

    public function render(string $indentation): string
    {
        $output = [];
        $classname = get_class($this);

        $output[] = $this->renderComment($indentation);
        $output[] = sprintf(
            self::STATE,
            $indentation,
            $this->getId(),
            $this->getStyleClass(),
            strtolower(substr($classname, strrpos($classname, '\\') + 1))
        );

        return implode("\n", array_filter($output, fn($v) => !empty($v)));
    }
}