# ARD Audiothek rss feed generator

## Dependencies
 - php-curl

## Usage

Simply pass the id of the show with the show parameter

Show url: https://www.ardaudiothek.de/sendung/wild-wild-web-geschichten-aus-dem-internet/urn:ard:show:58fb576ce81ae653/

Id: 94702896

Feed url: https://example.com/ardaudiothek-rss.php?show=94702896

If you only want to receive the n newest episodes, pass the latest parameter too:

Feed url with 10 newest episodes: https://example.com/ardaudiothek-rss.php?show=94702896&latest=10

## Helper page (index.html)

This repository includes a small helper page at /index.html that explains how to generate an RSS feed for a show and helps you find the numeric **show id**

## Deployment

Below are two simple ways to run the service: using Docker or Docker Compose. Replace port `8080` with any free port if needed.

- Docker (prebuilt image)
  - `docker run -d --name ard-audiothek-rss -p 8080:80 ghcr.io/matztam/ard-audiothek-rss:latest`
- Docker (build locally)
  - `docker build -t ard-audiothek-rss .`
  - `docker run -d --name ard-audiothek-rss -p 8080:80 ard-audiothek-rss`
- Docker Compose (using the included file)
  - `docker compose up -d`
  - Alternatively: `docker-compose up -d`

### How to access

- Index page: `http://localhost:8080/` (or `http://<host>:<port>/`)
- RSS feed with show ID: `http://localhost:8080/ardaudiothek-rss.php?show=10777871`
- Only the last X episodes: `http://localhost:8080/ardaudiothek-rss.php?show=10777871&latest=10`
