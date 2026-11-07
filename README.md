# Siddha Child Care – Pediatric Knowledge Dissemination System

Responsive PHP + SQLite application for XAMPP. No RAG/vector database is used.

## Features
- Parent/General Public and Professional login roles
- Topic → subtopic → detailed content navigation
- Search across site content
- Saved/bookmarked content
- Admin CRUD for topics, subtopics and content
- Tamil/English-ready UTF-8 database fields
- Optional ASK AI widget: searches relevant SQLite content first and sends only those passages to an OpenAI-compatible API. If no API key is configured, it still provides local site search results.
- PDO prepared statements, password_hash/password_verify, CSRF tokens, sessions, role checks
- Mobile responsive Bootstrap-style custom CSS (no external dependency required)

## Install on XAMPP
1. Copy `siddha_child_care` into `C:\xampp\htdocs\`.
2. Ensure PHP 8.2+ has `pdo_sqlite` and `sqlite3` enabled.
3. Visit `http://localhost/siddha_child_care/install.php` once.
4. Delete or rename `install.php` after installation.
5. Login with the credentials shown by the installer and change the password immediately.

## Optional ASK AI
Edit `config/config.php` and set `OPENAI_API_KEY`, `OPENAI_MODEL`, and optionally `OPENAI_BASE_URL`.
The widget is intentionally separate from the site's normal search. It does not build a RAG index or vector store. It performs SQLite keyword/full-text retrieval and uses the retrieved site content as context for the AI response.

If no API key is configured, ASK AI falls back to a clearly labelled local knowledge search response.


TROUBLESHOOTING / XAMPP COMPATIBILITY
-------------------------------------
This package is intended for XAMPP on Windows with PHP 8.2.
The application does not require Apache rewrite rules or .htaccess.

Required PHP extensions:
- PDO
- PDO_SQLite
- SQLite3
- mbstring
- cURL (only needed for optional ASK AI API calls)

If Apache reports "Internal Server Error", check:
C:\xampp\apache\logs\error.log

After extracting this package, open:
http://localhost/siddha_child_care/install.php

Delete install.php after successful installation.

ACCESS CONTROL UPDATE
---------------------
Public/parent content is available without login. Professional content is restricted to authenticated users whose role is `professional` or `admin`. Direct URL access, search results, and ASK AI context are all filtered by this same policy.
