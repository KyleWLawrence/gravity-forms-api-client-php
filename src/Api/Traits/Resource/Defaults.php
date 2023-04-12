<?php

namespace KyleWLawrence\GForms\Api\Traits\Resource;

/**
 * This trait gives resources access to the default CRUD methods.
 */
trait Defaults
{
    use Get;
    use GetAll;
    use Delete;
    use Create;
    use Update;
}
