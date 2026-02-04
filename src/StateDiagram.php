<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\ClassDefTrait;
use BeastBytes\Mermaid\CommentTrait;
use BeastBytes\Mermaid\Diagram;
use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\DirectionTrait;
use BeastBytes\Mermaid\Mermaid;
use BeastBytes\Mermaid\RenderItemsTrait;
use BeastBytes\Mermaid\TitleTrait;

final class StateDiagram extends Diagram
{
    use CommentTrait;
    use ClassDefTrait;
    use DirectionTrait;
    use RenderItemsTrait;
    use StateTrait;
    use TitleTrait;
    use TransitionTrait;

    private const TYPE = 'stateDiagram-v2';

    protected function renderDiagram(): string
    {
        $output = [];

        $output[] = $this->renderTitle('');
        $output[] = $this->renderComment('');
        $output[] = self::TYPE;

        if ($this->direction !== Direction::topBottom) {
            $output[] = Mermaid::INDENTATION . $this->getDirection();
        }

        $output[] = $this->renderItems($this->states, '');
        $output[] = $this->renderItems($this->transitions, '');
        $output[] = $this->renderClassDefs();

        return implode("\n", array_filter($output, fn($v) => !empty($v)));
    }
}