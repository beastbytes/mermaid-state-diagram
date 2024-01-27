<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\ClassDefTrait;
use BeastBytes\Mermaid\CommentTrait;
use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\DirectionTrait;
use BeastBytes\Mermaid\Mermaid;
use BeastBytes\Mermaid\MermaidInterface;
use BeastBytes\Mermaid\RenderItemsTrait;
use BeastBytes\Mermaid\TitleTrait;
use Stringable;

final class StateDiagram implements MermaidInterface, Stringable
{
    use CommentTrait;
    use ClassDefTrait;
    use DirectionTrait;
    use RenderItemsTrait;
    use StateTrait;
    use TitleTrait;
    use TransitionTrait;

    private const TYPE = 'stateDiagram-v2';

    public function __toString(): string
    {
        return $this->render();
    }

    public function render(array $attributes = []): string
    {
        $output = [];

        $this->renderTitle($output);
        $this->renderComment('', $output);

        $output[] = self::TYPE;

        if ($this->direction !== Direction::TB) {
            $output[] = Mermaid::INDENTATION . $this->getDirection();
        }

        $this->renderItems($this->states, '', $output);
        $this->renderItems($this->transitions, '', $output);
        $this->renderClassDefs($output);

        return Mermaid::render($output, $attributes);
    }
}
