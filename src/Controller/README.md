# Controllers

### Abstract controller

Creating `$pagitor` with `DoctrineORMAdapter` is probably the most common use case.

You can also implement `createMongoPaginagor(Request $request, Query $query)` using `DoctrineORMAdapter` in a very similar way.

If you need method `elasticSearchPagination()` you can build according to [Pagerfanta Documentation]((https://github.com/whiteoctober/Pagerfanta#elasticaadapter))

```php
<?php
# src/Controller/AbstractController.php
    
    public function createPaginator(Request $request, Query $query): Pagerfanta
    {
        //  Construct the doctrine adapter using the query.
        $adapter = new DoctrineORMAdapter($query);
        $paginator = new Pagerfanta($adapter);

        //  Set pages based on the request parameters.
        $paginator->setMaxPerPage($request->query->get('limit', 10));
        $paginator->setCurrentPage($request->query->get('page', 1));

        return $paginator;
    }
}
```

This method allows you to forget about handling `LexikFormFilter` and simplify listing your data with filters controllers.  

```php
<?php
# src/Controller/AbstractController.php
    
    public function handleFilterForm(Request $request, string $filterForm): Pagerfanta
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->getRepository();
        $queryBuilder = $repository->getQueryBuilder();

        $form = $this->getForm($filterForm);

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            $queryBuilder = $this->get('lexik_form_filter.query_builder_updater')
                ->addFilterConditions($form, $queryBuilder);
        }

        $paginagor = $this->createPaginator($request, $queryBuilder->getQuery());

        return $paginagor;
    }
}
```

# Controllers

Controllers follow implements `list`, `show`, `create`, `update` and `delete` actions.

I implemented **GET**, **POST**, **PATCH** and **DELETE** request methods. I haven't included **PUT** in favor of **PATCH**. 

It's up to you what methods do you want to implement and what additional endpoint you need to create.
