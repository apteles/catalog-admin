<?php
declare(strict_types=1);

namespace Core\Shared\Domain\Validations;

use Core\Domain\Entities\Entity;
use Core\Domain\Validations\Validation;
use Rakit\Validation\Validator;

class VideoRakitValidator implements Validation
{

    public function validate(Entity $entity): void
    {
        $data = $this->toArray($entity);

        $validation = (new Validator())->validate($data, [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'yearLaunched' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        if ($validation->fails()) {
            foreach ($validation->errors()->all() as $error) {
                $entity->notification->addError([
                    'context' => 'video',
                    'message' => $error[0],
                ]);
            }
        }
    }

    private function toArray(Entity $entity): array
    {
        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'yearLaunched' => $entity->yearLaunched,
            'duration' => $entity->duration,
        ];
    }
}
