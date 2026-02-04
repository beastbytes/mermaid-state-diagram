<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\Mermaid;
use BeastBytes\Mermaid\RenderItemsTrait;

final class Group
{
    use RenderItemsTrait;
    use StateTrait;
    use TransitionTrait;

    private const string CONCURRENCY_OPERATOR = '--';

    /** @internal */
    public function render(string $indentation): string
    {
        $indentation = substr($indentation, 0, strlen(Mermaid::INDENTATION) * -1);
        $output = [];

        $output[] = $this->renderItems($this->states, $indentation,);
        $output[] = $this->renderItems($this->transitions, $indentation);
        $output[] = $indentation . Mermaid::INDENTATION . self::CONCURRENCY_OPERATOR;

        return implode("\n", array_filter($output, fn($v) => !empty($v)));
    }
}