# Stellar Security – Support Client (Laravel)

This package is a **client** for the Stellar Support Ticket API.  
It does **not** expose routes/controllers/migrations. It only calls your Support API over HTTP.

## Install (local path during dev)

In your Laravel project `composer.json`:

```json
{
  "repositories": [
    { "type": "path", "url": "packages/stellarsecurity/support-client-laravel" }
  ]
}
```

Then:

```bash
composer require stellarsecurity/support-client-laravel:@dev
php artisan vendor:publish --tag=stellar-support-config
```

## Env

```env
STELLAR_SUPPORT_BASE_URL=https://your-support-api-host
STELLAR_SUPPORT_BASIC_USER=stellar
STELLAR_SUPPORT_BASIC_PASS=supersecret
STELLAR_SUPPORT_TIMEOUT=15
STELLAR_SUPPORT_API_PREFIX=/api/v1
```

## Usage

```php
use StellarSecurity\SupportClient\Facades\Support;

$ticket = Support::createTicket([
  'product' => 'stellar-antivirus',
  'topic' => 'general_question',
  'subject' => 'Auto protection issue',
  'message' => '...',
  'preferred_language' => 'en',
  'email' => 'user@example.com',
  'user_ref' => 'user-123',
]);

Support::addMessage($ticket['data']['id'], [
  'author_type' => 'user',
  'author_ref' => 'user-123',
  'message' => 'More info...'
]);

Support::listTicketsByUserRef('user-123');
```
