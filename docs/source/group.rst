Group Class
===========

.. php:class:: Group

    Represents a group of states and transitions that are concurrent with other groups

    .. php:method:: __construct()

        Create a Group

        :returns: An instance of ``Group``
        :rtype: Group

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
