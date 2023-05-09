<?php
namespace App\Traits;

trait SenderTrait{

    public function create_mail_campaign()
    {
        $client = new \GuzzleHttp\Client();
        $json = [
            "title" => "Example campaign",
            "subject" => "Example campaign subject",
            "from" => "Sender support",
            "reply_to" => "support@sender.net",
            "content_type" => "text",
            "google_analytics" => 1,
            "auto_followup_subject" => "Example follow up subject",
            "auto_followup_delay" => 72,
            "auto_followup_active" => 1,
            "groups" => ["eZVD4w", "dN9n8z"],
            "segments" => ["elY9Ma"],
            "content" => "Adding the first content of my campaign"
        ];

        $response = $client->post(
            'https://api.sender.net/v2/campaigns',
            [
                'headers' => [
                    'Authorization' => 'Bearer [your-token]',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $json
            ]
        );
        $body = $response->getBody();
    }
}