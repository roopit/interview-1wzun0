<?php

namespace Collegeplannerpro\InterviewReport;

class MailerResult
{
    public function __construct(public readonly bool $isSuccess, public readonly ?string $errorMessage = null) {}

    public static function success(): MailerResult
    {
        return new self(isSuccess: true);
    }

    public static function error($errorMessage): MailerResult
    {
        return new self(isSuccess: false, errorMessage: $errorMessage);
    }
}
