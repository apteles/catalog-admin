<?php
declare(strict_types=1);

namespace Core\Domain\Validations;

final class Validate
{
    /**
     * @var array<string, class-string>
     */

    /**
     * @phpstan-ignore-next-line
     */
    private static array $validators = [
        'string' => Str::class
    ];

    public static function if(string $validator, mixed $value, array|string $validations)
    {
//        if(is_array($validations)) {
//            foreach ($validations as $validation) {
//               preg_match('/(\w+)::(\w+)\((.*?)\)/',$validation, $matches);
//               if(method_exists(static::$validators[$validator])) {
//                    [,$class, $method, $arguments] = $matches;
//                   $class::
//               }
//            }
//        }

    }



}

Validate::if('string', 'test', ['isNotEmpty']);
