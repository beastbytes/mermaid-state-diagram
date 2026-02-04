Fork Class
==========

.. php:class:: Fork

    Represents a Fork

    Implements :php:interface:`StateInterface`

    .. php:method:: __construct(?string $id)

        Create a Fork

        :param ?string $id: The state id (default: auto-generate)
        :returns: An instance of ``Fork``
        :rtype: Fork

    .. php:method:: withComment(string $comment)

        Add a comment

        :param string $comment: The comment
        :returns: A new instance of ``Fork`` with the comment
        :rtype: Fork
