<?php

namespace App\Commands;

use App\Models\Performer;
use App\Services\MusicBrainz\MusicBrainzClientInterface;
use App\Services\MusicBrainz\MusicBrainzNormalizer;
use LaravelZero\Framework\Commands\Command;

class MbLookupCommand extends Command
{
    protected $signature = 'mb:lookup {name : Artist name to search for}';
    protected $description = 'Search MusicBrainz for an artist and store their data';

    public function handle(
        MusicBrainzClientInterface $client,
        MusicBrainzNormalizer $normalizer,
    ): int {
        $name = $this->argument('name');
        $this->info("Searching MusicBrainz for \"{$name}\"...");

        $results = $client->searchArtist($name);
        $artists = $results['artists'] ?? [];

        if (empty($artists)) {
            $this->warn('No artists found.');

            return self::FAILURE;
        }

        // Display search results
        $rows = array_map(fn (array $artist, int $i) => [
            $i + 1,
            $artist['name'],
            $artist['type'] ?? '-',
            $artist['disambiguation'] ?? '-',
            $artist['country'] ?? '-',
        ], $artists, array_keys($artists));

        $this->table(['#', 'Name', 'Type', 'Disambiguation', 'Country'], $rows);

        // Let user pick one
        $choice = (int) $this->ask('Select an artist number to fetch full details (0 to cancel)', '1');

        if ($choice < 1 || $choice > count($artists)) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        $selected = $artists[$choice - 1];
        $mbid = $selected['id'];

        $this->info("Fetching full details for {$selected['name']} ({$mbid})...");

        // Fetch full artist with aliases and relationships
        $raw = $client->lookupArtist($mbid);
        $normalized = $normalizer->normalize($raw);

        // Store performer
        $performer = Performer::create($normalized['performer']);

        // Store aliases
        foreach ($normalized['aliases'] as $alias) {
            $performer->aliases()->create($alias);
        }

        // Store identifiers
        foreach ($normalized['identifiers'] as $identifier) {
            $performer->identifiers()->create($identifier);
        }

        // Summary
        $this->newLine();
        $this->info("Stored: {$performer->name}");
        $this->line("  Type: {$performer->type}");
        $this->line("  Country: {$performer->country}");
        $this->line("  Aliases: {$performer->aliases()->count()}");
        $this->line("  Identifiers:");
        foreach ($performer->identifiers as $id) {
            $this->line("    {$id->type}: {$id->value}");
        }

        return self::SUCCESS;
    }
}
