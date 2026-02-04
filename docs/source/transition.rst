Transition Class
================

Represents a Transition between :php:class:`StateInterface` objects

.. php:class:: Transition

    .. php:method:: __construct(?StateInterface $from = null, ?StateInterface $to = null, ?string $label = null)

        Create a Transition

        :param ?StateInterface $from: The state to transition from (default: Start state)
        :param ?StateInterface $to: The state to transition to (default: End state)
        :param ?string $label: A label for the transition (default: no label)
        :returns: An instance of ``Transition``
        :rtype: Transition

    .. php:method:: withComment(string $comment)

        Add a comment

        :param string $comment: The comment
        :returns: A new instance of ``Transition`` with the comment
        :rtype: Transition
