<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\NodeInterface;
use BeastBytes\Mermaid\StyleClassTrait;

abstract class BaseState implements NodeInterface
{
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
        $classname = get_class($this);

        return $indentation
            . 'state '
            . $this->getId()
            . $this->getStyleClass()
            . ' <<'
            . strtolower(substr($classname, strrpos($classname, '\\') + 1))
            . '>>'
        ;
    }
}
