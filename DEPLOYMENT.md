# Deployment Checklist — Astra Library

Use this checklist before promoting the site to production.

1) Environment
- Ensure PHP version >= 7.4 (check compatibility with WP 6.9.1 or upgrade WP).
- Use a managed DB with daily backups or a backup strategy.

2) Credentials & Secrets
- Replace local DB credentials in `wp-config.php` with production credentials.
- Ensure `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY` and salts are unique and kept secret.
- Do NOT commit secrets; `.gitignore` already excludes `wp-config.php`.

3) HTTPS & Headers
- Configure SSL (HTTPS) and redirect HTTP → HTTPS.
- Add HSTS and security headers (Content-Security-Policy, X-Frame-Options, X-Content-Type-Options).

4) Hardening
- Keep `DISALLOW_FILE_EDIT` enabled (already provided by `wp-content/mu-plugins/library-security.php`).
- Limit access to `wp-admin` by IP where possible.

5) File Permissions
- Ensure files are owned by the webserver user and writable only where necessary (uploads, cache).

6) Plugins & Themes
- Update all plugins and themes to their latest stable releases.
- Remove unused plugins and themes.

7) Backups & Monitoring
- Configure daily DB backups and weekly full-site backups.
- Enable uptime monitoring and error reporting (Sentry, New Relic, or similar).

8) Performance
- Use object caching (Redis, Memcached) and page caching (Varnish or plugin).
- Offload static files to CDN if traffic justifies it.

9) Final Checks
- Disable `WP_DEBUG` and any debug logging.
- Run the `scripts/backup.ps1` locally and verify backup integrity.
- Rebuild search index (Relevanssi) after migration.

10) Deployment process
- Use zero-downtime deployment if possible; otherwise schedule maintenance window.
- Keep rollback steps ready: DB restore and previous code snapshot.
