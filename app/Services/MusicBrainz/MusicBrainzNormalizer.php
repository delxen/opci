<?php

namespace App\Services\MusicBrainz;

class MusicBrainzNormalizer
{
    /**
     * Known URL relationship types and their identifier types.
     * MB stores external IDs as URL relationships — we extract the ID from the URL.
     */
    private const URL_RELATION_MAP = [
        'wikidata' => [
            'pattern' => '#wikidata\.org/(?:wiki|entity)/(Q\d+)#',
            'type' => 'wikidata_qid',
        ],
        'ISNI' => [
            'pattern' => '#isni\.org/isni/(\d{15}[\dX])#',
            'type' => 'isni',
        ],
        'VIAF' => [
            'pattern' => '#viaf\.org/viaf/(\d+)#',
            'type' => 'viaf',
        ],
        'discogs' => [
            'pattern' => '#discogs\.com/artist/(\d+)#',
            'type' => 'discogs_id',
        ],
        'allmusic' => [
            'pattern' => '#allmusic\.com/artist/(mn\d+)#',
            'type' => 'allmusic_id',
        ],
        'IMDb' => [
            'pattern' => '#imdb\.com/name/(nm\d+)#',
            'type' => 'imdb_id',
        ],
    ];

    /**
     * Normalize a full artist lookup response into our internal format.
     */
    public function normalize(array $raw): array
    {
        return [
            'performer' => $this->normalizePerformer($raw),
            'aliases' => $this->normalizeAliases($raw['aliases'] ?? []),
            'identifiers' => $this->normalizeIdentifiers($raw),
        ];
    }

    private function normalizePerformer(array $raw): array
    {
        return [
            'source' => 'musicbrainz',
            'name' => $raw['name'],
            'type' => isset($raw['type']) ? strtolower($raw['type']) : null,
            'gender' => isset($raw['gender']) ? strtolower($raw['gender']) : null,
            'country' => $raw['country'] ?? null,
            'disambiguation' => $raw['disambiguation'] ?? null,
        ];
    }

    private function normalizeAliases(array $aliases): array
    {
        // Only keep aliases that have a locale — no locale means no useful context
        $filtered = array_filter($aliases, fn (array $alias) => isset($alias['locale']));

        return array_map(fn (array $alias) => [
            'name' => $alias['name'],
            'locale' => $alias['locale'],
            'primary' => $alias['primary'] ?? false,
        ], $filtered);
    }

    private function normalizeIdentifiers(array $raw): array
    {
        $identifiers = [];

        // The MBID itself
        $identifiers[] = [
            'type' => 'mbid',
            'value' => $raw['id'],
        ];

        // Extract IDs from URL relationships
        foreach ($raw['relations'] ?? [] as $relation) {
            if ($relation['type'] !== 'url' && ! isset($relation['url'])) {
                continue;
            }

            $url = $relation['url']['resource'] ?? '';

            foreach (self::URL_RELATION_MAP as $relationType => $config) {
                if ($relation['type'] !== $relationType) {
                    continue;
                }

                if (preg_match($config['pattern'], $url, $matches)) {
                    $identifiers[] = [
                        'type' => $config['type'],
                        'value' => $matches[1],
                    ];
                }
            }
        }

        return $identifiers;
    }
}
