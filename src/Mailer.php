<?php

namespace Collegeplannerpro\InterviewReport;

class Mailer
{
    /**
     * @param string[] $recipientEmails
     * @param string $subject
     * @param string $body
     * @return MailerResult
     */
    public function send(array $recipientEmails, string $subject, string $body): MailerResult
    {
        if (!$recipientEmails) {
            return MailerResult::success();
        }

        // TODO: we'll fill in a real implementation later

        // simulate a seemingly random failure of the mail backend
        $isError = rand(1, 100) % 5 === 0;

        if ($isError) {
            $faker = \Faker\Factory::create();
            return MailerResult::error("The {$faker->word()} is broken");
        }

        $otherCount = count($recipientEmails) - 1;
        $otherText = $otherCount ? " and $otherCount other recipient(s)" : '';
        $logMsg = <<<MSG
Sending message to $recipientEmails[0]$otherText:
===== BEGIN MESSAGE =====
Subject: $subject
$body
===== END MESSAGE =====
MSG;
        error_log($logMsg);

        return MailerResult::success();
    }
}
