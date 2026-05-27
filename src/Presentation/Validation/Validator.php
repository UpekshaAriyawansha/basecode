<?php

namespace Src\Presentation\Validation;

class Validator
{
    private array $errors = [];

    public function validate(
        array $data,
        array $rules
    ): bool {

        foreach ($rules as $field => $fieldRules) {

            $rulesArray =
                explode('|', $fieldRules);

            foreach ($rulesArray as $rule) {

                if (
                    $rule === 'required'
                    &&
                    empty($data[$field])
                ) {

                    $this->errors[$field][] =
                        "{$field} is required";
                }

                if (
                    $rule === 'email'
                    &&
                    !filter_var(
                        $data[$field] ?? '',
                        FILTER_VALIDATE_EMAIL
                    )
                ) {

                    $this->errors[$field][] =
                        "{$field} must be valid";
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}