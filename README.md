# OPCI

**Open Performer Credit Infrastructure** — a CLI tool for reconciling performer identities between MusicBrainz and Wikidata. Identifies missing cross-references and generates contribution payloads to enrich both platforms.

## Requirements

PHP 8.2 or higher with the `pdo_sqlite` extension (included by default in most PHP installations).

### Installing PHP

**Windows**
1. Download the latest **VS17 x64 Non Thread Safe** zip from [windows.php.net/download](https://windows.php.net/download/)
2. Extract to a folder (e.g. `C:\php`)
3. Add that folder to your system PATH
4. Verify: `php -v`

**macOS**
```bash
brew install php
```

**Linux (Debian/Ubuntu)**
```bash
sudo apt install php php-sqlite3
```

**Linux (Fedora/RHEL)**
```bash
sudo dnf install php php-pdo
```

## Installation

### Download PHAR (recommended)

Download `opci.phar` from the latest [Release](../../releases).

```bash
php opci.phar
```

That's it — no other dependencies needed.

### From source

```bash
git clone https://github.com/delxen/opci.git
cd opci
composer install
php opci
```

## Usage

### Look up an artist on MusicBrainz

```bash
php opci.phar mb:lookup "Mikis Theodorakis"
```

Searches MusicBrainz by name, lets you select a result, fetches full details (aliases, identifiers like ISNI, VIAF, Wikidata QID), and stores everything in a local SQLite database.

## Development

```bash
# Run the app
php opci

# Run tests
php opci test

# Build PHAR
php opci app:build opci
```

## License

Apache 2.0
