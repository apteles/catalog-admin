<?php
declare(strict_types=1);

namespace Core\Domain\Events;

use Core\Domain\Entities\Video;

class VideoCreatedEvent implements Event
{


    public function __construct(
        private readonly Video $video
    ){
    }

    public function getEventName(): string
    {
        return 'video.created';
    }

    /**
     * @return array<string, string>
     */
    public function getPayload(): mixed
    {
        return [
            'resource_id' => $this->video->id(),
            'file_path' => $this->video->videoFile()->filePath,
        ];
    }
}
