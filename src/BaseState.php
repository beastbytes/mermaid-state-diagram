<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\CommentTrait;
use BeastBytes\Mermaid\NodeInterface;
use BeastBytes\Mermaid\StyleClassTrait;

abstract class BaseState implements NodeInterface
{
    use CommentTrait;
    use StyleClassTrait;

    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return '_' . $this->id;
    }

    public function render(string $indentation): string
    {
        $output = [];
        $classname = get_class($this);

        $this->renderComment($indentation, $output);
        $output[] = $indentation
            . 'state '
            . $this->getId()
            . $this->getStyleClass()
            . ' <<'
            . strtolower(substr($classname, strrpos($classname, '\\') + 1))
            . '>>'
        ;

        return implode("\n", $output);
    }
}
