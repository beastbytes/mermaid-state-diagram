StateDiagram Class
==================

.. php:class:: StateDiagram

    Represents a state diagram

    .. php:method:: addClassDef(array $classDef)

        Add class definition(s)

        :param array $classDef: The class definition(s)
        :returns: A new instance of StateDiagram with the class definition(s) added
        :rtype: StateDiagram

    .. php:method:: addState(StateInterface ...$state)

        Adds state(s)

        :param StateInterface ...$state: The state(s)
        :returns: A new instance of StateDiagram with the state(s) added
        :rtype: StateDiagram

    .. php:method:: addTransition(Transition ...$transition)

        Adds transition(s)

        :param Transition ...$transition: The transition(s)
        :returns: A new instance of StateDiagram with the transition(s) added
        :rtype: StateDiagram

    .. php:method:: render(array $attributes = [])

        Renders the diagram

        :param array $attributes: HTML attributes for the <pre> tag as name=>value pairs

            .. note:: The *mermaid* class is added

        :returns: Mermaid diagram code in a <pre> tag
        :rtype: string

    .. php:method:: withClassDef(array $classDef)

        Set class definition(s)

        :param array $classDef: The class definition(s)
        :returns: A new instance of StateDiagram with the class definition(s)
        :rtype: StateDiagram

    .. php:method:: withComment(string $comment)

        Set a comment

        :param string $comment: The comment
        :returns: A new instance of StateDiagram with a comment
        :rtype: StateDiagram

    .. php:method:: withDirection(Direction $direction)

        Set the diagram direction

        :param Direction $direction: The direction
        :returns: A new instance of StateDiagram with the direction set
        :rtype: StateDiagram

    .. php:method:: withState(StateInterface ...$state)

        Set state(s)

        :param StateInterface ...$state: The state(s)
        :returns: A new instance of StateDiagram with the state(s)
        :rtype: StateDiagram

    .. php:method:: withTransition(Transition ...$transition)

        Sets transition(s)

        :param Transition ...$transition: The transition(s)
        :returns: A new instance of StateDiagram with the transition(s)
        :rtype: StateDiagram
