<?php

namespace StellarSecurity\SupportClient\DTO;

class AddMessageRequest
{
    public function __construct(
        public string $author_type,
        public string $message,
        public ?string $author_ref = null,
    ) {}

    public function toArray(): array
    {
        return [
            'author_type' => $this->author_type,
            'author_ref' => $this->author_ref,
            'message' => $this->message,
        ];
    }
}
