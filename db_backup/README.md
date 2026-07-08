# HPCL Daily Database Backup (Cron)

Secure daily MySQL dump for this project. Backups are stored under date folders and committed to the project GitHub repo.

## Folder layout

```
hpcl/
  db_backup/
    backup-db.sh          # cron script
    .env.example          # template (safe to commit)
    .env                  # REAL secrets — never commit
    .htaccess             # deny web access
    backup-db.log         # runtime log (gitignored)
    db_backups/
      YYYY-MM-DD/
        hpcl_YYYY-MM-DD_HHMMSS.sql.gz
```

Preferred secret location (best practice on cPanel):

```
/home/YOURUSER/private/hpcl_db_backup.env
```

Or set env var `HPCL_DB_BACKUP_ENV` to that absolute path.

## One-time hosting setup

1. Upload / pull this `db_backup` folder onto the server (outside or next to the app).
2. Copy secrets:

```bash
cp db_backup/.env.example db_backup/.env
# edit db_backup/.env  OR create ~/private/hpcl_db_backup.env
chmod 600 db_backup/.env
chmod 700 db_backup
chmod 755 db_backup/backup-db.sh
```

3. Fill `.env` (or private env file):

| Variable | Example |
|----------|---------|
| `DB_HOST` | `localhost` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `hpcl` |
| `DB_USER` | hosting DB user |
| `DB_PASS` | hosting DB password |
| `GIT_REMOTE` | `origin` |
| `GIT_BRANCH` | `main` |
| `RETENTION_DAYS` | `14` |

4. Ensure server Git can push to the **private** GitHub repo:
   - Deploy SSH key on the hosting account, **or**
   - HTTPS remote with a fine-grained PAT stored for the cron user
5. Test once over SSH:

```bash
/bin/bash /home/YOURUSER/public_html/hpcl/db_backup/backup-db.sh
tail -n 50 /home/YOURUSER/public_html/hpcl/db_backup/backup-db.log
```

## cPanel cron (daily 2:00 AM)

In **cPanel → Cron Jobs**, add:

```bash
0 2 * * * /bin/bash /home/YOURUSER/public_html/hpcl/db_backup/backup-db.sh >> /home/YOURUSER/public_html/hpcl/db_backup/backup-db.log 2>&1
```

Replace `YOURUSER` and path with your real home + project path.

If secrets live outside web root:

```bash
0 2 * * * HPCL_DB_BACKUP_ENV=/home/YOURUSER/private/hpcl_db_backup.env /bin/bash /home/YOURUSER/public_html/hpcl/db_backup/backup-db.sh >> /home/YOURUSER/public_html/hpcl/db_backup/backup-db.log 2>&1
```

## What the script does

1. Loads env from private path / `.env` (password never passed on CLI args).
2. Creates `db_backups/YYYY-MM-DD/`.
3. Runs `mysqldump` via a temporary `0600` defaults file.
4. Compresses to `.sql.gz`.
5. `git add` for that date folder inside the repo.
6. Commits with message `DB backup YYYY-MM-DD`.
7. Pushes to GitHub.
8. Appends status to `backup-db.log`.
9. On mysqldump / commit / push failure: logs `ERROR` and exits non-zero.

## Security notes

- Do **not** put DB password in the cron command line.
- Do **not** commit `.env`.
- `.htaccess` denies HTTP access to this folder; still prefer `~/private/` for secrets.
- Rotate DB password and Git credentials periodically.
- Large dumps will grow the Git repo; use `RETENTION_DAYS` and consider a separate backup repo if dumps get large.

## Local Laragon note

On Windows, run this script via Git Bash / WSL after filling `.env`, and ensure `mysqldump` is on PATH (Laragon MySQL `bin`).

```bash
# Git Bash example
"/c/laragon/bin/mysql/mysql-8.x/bin/mysqldump" --version
MYSQLDUMP_BIN="/c/laragon/bin/mysql/mysql-8.x/bin/mysqldump" bash db_backup/backup-db.sh
```
