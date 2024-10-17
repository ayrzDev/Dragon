<?php

namespace api\discord;

class DiscordWebhook
{
    protected $webhookUrl;

    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    public function sendMessage($message , $username = 'Webhook', $avatarUrl = null, $embeds = [])
    {
        $payload = [
            'content' => $message,
            'username' => $username,
            'avatar_url' => $avatarUrl,
            'embeds' => $embeds
        ];

        return $this->sendRequest($payload);
    }

    protected function sendRequest($payload)
    {
        $ch = curl_init($this->webhookUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return ['success' => true, 'response' => $response];
        } else {
            return ['success' => false, 'response' => $response, 'http_code' => $httpCode];
        }
    }
}
