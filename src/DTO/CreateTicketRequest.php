<?php

namespace StellarSecurity\SupportClient\DTO;

class CreateTicketRequest
{
    public function __construct(
        public string $product,
        public string $topic,
        public string $subject,
        public string $message,
        public string $email,
        public ?string $user_ref = null,
        public ?string $preferred_language = 'en',
    ) {}

    public function toArray(): array
    {
        return [
            'product' => $this->product,
            'topic' => $this->topic,
            'subject' => $this->subject,
            'message' => $this->message,
            'preferred_language' => $this->preferred_language,
            'email' => $this->email,
            'user_ref' => $this->user_ref,
        ];
    }
}
