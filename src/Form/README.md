# Event Forms and Filters

`AbsctractFormHandler` is responsible for process your JSON payload, validation and returning possible errors.


## Forms

Changes on Entity properties are possible because of Symfony Forms.

You need to include all **required properties** otherwise **POST** will not pass validation.

There is no need to include in **PATCH** payload all properties if you don't want to change them.


```php
<?php
# src/Form/Book

        $builder
            ->add('isbn')
            ->add('title')
            ->add('description');
``` 

## Filter

Filter forms allow filtering results. It's a great tool - to get familiar with `FilterForms` check out [LexikFormFilterBundle Documentation](https://github.com/lexik/LexikFormFilterBundle/blob/master/Resources/doc/index.md)
