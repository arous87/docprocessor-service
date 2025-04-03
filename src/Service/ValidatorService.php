<?php

namespace App\Service;

class ValidatorService
{
    /**
     * Validate the paper data.
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function validatePaperData(array $data): void
    {
        if (!isset($data['user_id'], $data['content'])) {
            throw new \InvalidArgumentException('Missing required fields: user_id and content');
        }
        if (!is_int($data['user_id']) || empty($data['content'])) {
            throw new \InvalidArgumentException('Invalid data types for user_id or content');
        }
        if (isset($data['anchor']) && !is_string($data['anchor'])) {
            throw new \InvalidArgumentException('Invalid data type for anchor');
        }
        if (isset($data['file_path']) && !is_string($data['file_path'])) {
            throw new \InvalidArgumentException('Invalid data type for file_path');
        }
    }

    /**
     * Validate the tag data.
     *
     * @param string $tagName
     * @throws \InvalidArgumentException
     */
    public function validateTagName(string $tagName): void
    {
        if (!isset($tagName)) {
            throw new \InvalidArgumentException('Missing required field: name');
        }
        if (!is_string($tagName)) {
            throw new \InvalidArgumentException('Invalid data type for name');
        }
    }

}