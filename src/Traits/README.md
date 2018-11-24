# Traits

`ControllerTrait` is the list of properties and setters.

```php
<?php
# src/Traits/ControllerTrait.php

trait ControllerTrait
{

    /**
     * @var Inflector
     */
    protected $inflector;

    /**
     * @required
     *
     * @param Inflector $inflector
     */
    public function setInflector(Inflector $inflector): void
    {
        $this->inflector = $inflector;
    }
```
Setters have `@required` parameter when you use this trait all setters are executed and set traits properties.

This trait is used to avoid passing a lot of parameters to  `AbstractController.php` 

`IdCollumnTrait` and `TimeAwareTrait` allow to reduce repetitive code.
