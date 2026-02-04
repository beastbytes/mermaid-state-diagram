State Class
===========

.. php:class:: State

    Represents a State

    .. php:method:: __construct(?string $id, ?string $label)

        Create a Choice

        :param ?string $id: The state id (default: auto-generate)
        :param ?string $label: A label for the state(default: use the id)
        :returns: An instance of ``Choice``
        :rtype: Choice

    Implements :php:interface:`StateInterface`

    .. php:method:: addGroup(Group ...$group)

        Adds group(s)

        :param Group ...$group: The group(s)
        :returns: A new instance of State with the group(s) added
        :rtype: State

    .. php:method:: addState(StateInterface ...$state)

        Adds state(s)

        :param StateInterface ...$state: The state(s)
        :returns: A new instance of State with the state(s) added
        :rtype: State

    .. php:method:: addTransition(Transition ...$transition)

        Adds transition(s)

        :param Transition ...$transition: The transition(s)
        :returns: A new instance of State with the transition(s) added
        :rtype: State

    .. php:method:: withComment(string $comment)

        Set a comment

        :param string $comment: The comment
        :returns: A new instance of State with a comment
        :rtype: State

    .. php:method:: withGroup(Group ...$group)

        Set group(s)

        :param Group ...$group: The group(s)
        :returns: A new instance of State with the group(s)
        :rtype: State

    .. php:method:: withState(StateInterface ...$state)

        Set state(s)

        :param StateInterface ...$state: The state(s)
        :returns: A new instance of State with the state(s)
        :rtype: State

    .. php:method:: withTransition(Transition ...$transition)

        Sets transition(s)

        :param Transition ...$transition: The transition(s)
        :returns: A new instance of State with the transition(s)
        :rtype: State
