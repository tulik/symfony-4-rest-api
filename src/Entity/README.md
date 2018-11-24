# Entities

## Exclusion Policy
By default all entities uses `ExclusionPolicy("ALL")`. 
The property will not appear in response until you add `@JMS\Expose` in annotations.

```php
<?php
# src/Entity/Book.php

/**
 * @JMS\ExclusionPolicy("ALL")
 */
class Book
{
    /**
     * @JMS\Expose
     */
    protected $title;

```
## Groups

```php
<?php
# src/Entity/Book.php

/**
 * @JMS\ExclusionPolicy("ALL")
 */
class Book
{
    /**
     * @JMS\Expose
     * @JMS\Groups("reviews")
     */
    protected $reviews;

```

To avoid response where the object contains all objects  from `Collection` use `@JMS\Groups("name_of_your_grour")`.

Later you will be able to expand this object with `expand=name_of_your_grour` parameter. 

For example `http://[host]/book?expand=reviews`
