<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\CommentTrait;
use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\DirectionTrait;
use BeastBytes\Mermaid\Mermaid;
use BeastBytes\Mermaid\RenderItemsTrait;
use BeastBytes\Mermaid\StyleClassTrait;

final class State extends BaseState
{
    use CommentTrait;
    use DirectionTrait;
    use RenderItemsTrait;
    use StateTrait;
    use StyleClassTrait;
    use TransitionTrait;

    private const string STATE = 'state "%s" as %s%s';

    private array $groups = [];
    private ?Note $note = null;

    public function __construct(
        string $id,
        private ?string $label = null
    )
    {
        parent::__construct($id);

        if ($this->label === null) {
            $this->label = $id;
        }
    }

    public function addGroup(Group ...$group): self
    {
        $new = clone $this;
        $new->groups = array_merge($new->groups, $group);
        return $new;
    }

    public function withGroup(Group ...$group): self
    {
        $new = clone $this;
        $new->groups = $group;
        return $new;
    }

    public function withNote(Note $note): self
    {
        $new = clone $this;
        $new->note = $note;
        return $new;
    }

    /** @internal */
    public function render(string $indentation): string
    {
        $output = [];

        $output[] = $this->renderComment($indentation);
        $output[] = $indentation . sprintf(
            self::STATE,
            $this->label,
            $this->getId(),
            $this->getStyleClass()
        );

        if (count($this->states) > 0 || count($this->groups) > 0) {
            $output[1] .= ' {';

            if ($this->direction !== Direction::topBottom) {
                $output[] = $indentation . Mermaid::INDENTATION . $this->getDirection();
            }

            if (count($this->states) > 0) {
                $output[] = $this->renderItems($this->states, $indentation);
                $output[] = $this->renderItems($this->transitions, $indentation);
            }

            if (count($this->groups) > 0) {
                $output[] = $this->renderItems($this->groups, $indentation);
                $groups = array_pop($output);
                $output[] = substr($groups, 0, strrpos($groups, "\n")); // remove final concurrency operator
            }

            $output[] = $indentation . '}';
        }

        if ($this->note !== null) {
            $output[] = $this
                ->note
                ->render($indentation, $this)
            ;
        }

        return implode("\n", array_filter($output, fn($v) => !empty($v)));
    }
}