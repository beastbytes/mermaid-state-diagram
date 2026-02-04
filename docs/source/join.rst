Join Class
==========

.. php:class:: Join

    Represents a Join

    Implements :php:interface:`StateInterface`

    .. php:method:: __construct(?string $id)

        Create a Join

        :param ?string $id: The state id (default: auto-generate)
        :returns: An instance of ``Join``
        :rtype: Join

    .. php:method:: withComment(string $comment)

        Add a comment

        :param string $comment: The comment
        :returns: A new instance of ``Join`` with the comment
        :rtype: Join
