<?php
namespace App\Exceptions;

use DomainException;
use Illuminate\Validation\Validator;
use Validator as ValidatorFacade;

class ValidationException extends DomainException
{
    public static function validate($input, $rules)
    {
        $val = ValidatorFacade::make($input, $rules);
        return static::fromValidator($val);
    }

    public static function fromValidator(Validator $val)
    {
        if ($val->fails())
            throw new static($val->errors()->all());
    }

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        if (is_array($message))
            $message = json_encode($message);

        parent::__construct($message, $code, $previous);
    }

    public function getErrors()
    {
        return json_decode($this->getMessage(), true);
    }
}