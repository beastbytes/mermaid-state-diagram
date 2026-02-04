Choice Class
============

.. php:class:: Choice

    Represents a Choice

    Implements :php:interface:`StateInterface`

    .. php:method:: __construct(?string $id)

        Create a Choice

        :param ?string $id: The state id (default: auto-generate)
        :returns: An instance of ``Choice``
        :rtype: Choice

    .. php:method:: withComment(string $comment)

        Add a comment

        :param string $comment: The comment
        :returns: A new instance of ``Choice`` with the comment
        :rtype: Choice
