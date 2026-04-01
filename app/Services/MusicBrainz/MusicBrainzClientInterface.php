<?php

namespace App\Services\MusicBrainz;

interface MusicBrainzClientInterface
{
    /**
     * Search for artists by name.
     *
     * @return array Raw decoded JSON response from MB API
     */
    public function searchArtist(string $query, int $limit = 10): array;

    /**
     * Look up a specific artist by MBID, including aliases and URL relationships.
     *
     * @return array Raw decoded JSON response from MB API
     */
    public function lookupArtist(string $mbid): array;
}
