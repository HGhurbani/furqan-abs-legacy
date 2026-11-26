# Furqan ABS — legacy travel agency system

Private source import for review and modernization. PHP / CodeIgniter 2.1.4 with MySQL; includes the main web application, legacy mobile application, and bundled assets.

## Import contents

- Original application source, with literal database credentials and configured encryption/SMTP/API secrets replaced by environment variable reads where detected.
- `database/schema.sql`: 118 tables, structure only, from the separately supplied 2015 backup. Compatibility with this later source snapshot has not been verified.
- Excluded: database records and dumps, booking interchange files, uploaded files, spreadsheets, runtime logs, caches, temporary files, and backup directories. The original archive and database remain local and are not committed.

## Configuration

Set the names listed in `.env.example` in the PHP process environment. This project does not automatically load `.env` files. Configure `application/user/config/` and the corresponding mobile configuration as needed. Runtime directories excluded from this import must be recreated with appropriate permissions.

## Status

This is an archival import, not a production release. No full application startup or financial reconciliation was performed. Legacy mysql_* usage prevents use on modern PHP without migration. Authentication uses MD5, remember-me cookies retain passwords, SQL queries concatenate input, and CSRF protection is disabled in the supplied configuration. The supplied schema uses MyISAM and requires a transaction-safe migration plan for accounting operations. These existing issues have not been repaired by this import.

No new redistribution license is granted by this repository. Existing bundled third-party notices remain in source.
