<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\ClassDefTrait;
use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\DirectionTrait;
use BeastBytes\Mermaid\Mermaid;
use BeastBytes\Mermaid\MermaidInterface;
use BeastBytes\Mermaid\RenderItemsTrait;
use BeastBytes\Mermaid\TitleTrait;
use Stringable;

final class StateDiagram implements MermaidInterface, Stringable
{
    use ClassDefTrait;
    use DirectionTrait;
    use RenderItemsTrait;
    use StateTrait;
    use TitleTrait;
    use TransitionTrait;

    private const TYPE = 'stateDiagram-v2';

    public function __construct(
        private readonly string $title = ''
    )
    {
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function render(): string
    {
        $output = [];

        if ($this->title !== '') {
            $output[] = $this->getTitle();
        }

        $output[] = self::TYPE;

        if ($this->direction !== Direction::TB) {
            $output[] = $this->renderDirection(Mermaid::INDENTATION);
        }

        $output[] = $this->renderItems($this->states, '');

        if (count($this->transitions) > 0) {
            $output[] = $this->renderItems($this->transitions, '');
        }

        if (!empty($this->classDefs)) {
            $output[] = $this->renderClassDefs(Mermaid::INDENTATION);
        }

        return Mermaid::render($output);
    }
}
