# Security and Voters

## UserProvider
`UserProvider` is an implementation of `UserProviderInterface` build based on Symfony [All about User Providers](https://symfony.com/doc/current/security/user_provider.html) documentation.

## Voters
Symfony voters are the most granular and flexible way of checking permissions to perform an action by User.

Check out []How to Use Voters to [Check User Permissions](https://symfony.com/doc/current/security/voters.html) to get more familiar with Voters.

In Controllers, they check for permissions to perform a specific action.

Take a look on `@Security("is_granted('CAN_CREATE_BOOK', book)")` annotation.

```php
<?php
# src/Controller/Book.php

    /**
     * Add new Book.
     *
     * @Route(name="api_book_create", methods={Request::METHOD_POST})
     * @param Request $request
     * @param Book|null $book
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_CREATE_BOOK', book)")
     */
    public function createAction(Request $request, Book $book = null): JsonResponse
```

In this case `$attribute` is `CAN_CREATE_BOOK` and the subject is `NULL`.

```php
<?php
# src/Controller/Book.php

    /**
     * Edit existing Book.
     *
     * @param Request $request
     * @param Book|null $book
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_UPDATE_BOOK', book)")
     */
    public function updateAction(Request $request, Book $book = null): JsonResponse
```

In this case `$attribute` is `CAN_CREATE_BOOK` and the subject is `Book` entity.
