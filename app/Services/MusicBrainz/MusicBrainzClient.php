<?php

namespace App\Services\MusicBrainz;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MusicBrainzClient implements MusicBrainzClientInterface
{
    private const BASE_URL = 'https://musicbrainz.org/ws/2';
    private const CONTACT = 'https://github.com/delxen/opci';
    private const RATE_LIMIT_SECONDS = 1;

    private float $lastRequestTime = 0;

    private function userAgent(): string
    {
        $version = config('app.version', 'dev');

        return "OPCI/{$version} (".self::CONTACT.')';
    }

    public function searchArtist(string $query, int $limit = 10): array
    {
        return $this->request('/artist', [
            'query' => $query,
            'fmt' => 'json',
            'limit' => $limit,
        ]);
    }

    public function lookupArtist(string $mbid): array
    {
        return $this->request("/artist/{$mbid}", [
            'fmt' => 'json',
            'inc' => 'aliases+url-rels',
        ]);
    }

    private function request(string $path, array $params): array
    {
        $this->rateLimit();

        $response = Http::withHeaders([
            'User-Agent' => $this->userAgent(),
            'Accept' => 'application/json',
        ])->get(self::BASE_URL.$path, $params);

        if ($response->failed()) {
            throw new RuntimeException(
                "MusicBrainz API error: {$response->status()} {$response->body()}"
            );
        }

        return $response->json();
    }

    private function rateLimit(): void
    {
        $now = microtime(true);
        $elapsed = $now - $this->lastRequestTime;

        if ($this->lastRequestTime > 0 && $elapsed < self::RATE_LIMIT_SECONDS) {
            usleep((int) ((self::RATE_LIMIT_SECONDS - $elapsed) * 1_000_000));
        }

        $this->lastRequestTime = microtime(true);
    }
}
