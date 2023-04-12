<?php

namespace KyleWLawrence\GForms\Api\Exceptions;

/**
 * InvalidParametersException extends the Exception class with simplified messaging
 */
class InvalidParametersException extends \Exception
{
    /**
     * @param  string  $method
     * @param  array  $invalid
     * @param  array  $valid
     * @param  int  $code
     * @param  \Exception  $previous
     */
    public function __construct($method, array $invalid, array $valid, $code = 0, \Exception $previous = null)
    {
        parent::__construct(
            'Invalid parameter for \''.implode("', '", $invalid).'\' supplied. Valid parameters are limited to [\''.implode("', '", $valid).'\'] for '.$method,
            $code,
            $previous
        );
    }
}
