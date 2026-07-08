#!/usr/bin/env bash
# Secure daily MySQL backup -> date folder -> git commit/push
# Usage (cPanel cron): /bin/bash /home/USER/path/to/hpcl/db_backup/backup-db.sh
# Prefer placing .env outside web root; see README.md

set -u

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_FILE="${SCRIPT_DIR}/backup-db.log"
DATE_TAG="$(date +%Y-%m-%d)"
TIME_TAG="$(date +%H%M%S)"
TMP_CNF=""
EXIT_CODE=0

log() {
  local level="$1"
  shift
  printf '[%s] [%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$level" "$*" | tee -a "$LOG_FILE"
}

die() {
  log "ERROR" "$*"
  cleanup
  EXIT_CODE=1
  exit 1
}

cleanup() {
  if [[ -n "${TMP_CNF}" && -f "${TMP_CNF}" ]]; then
    rm -f "${TMP_CNF}"
  fi
}

trap cleanup EXIT

load_env() {
  local env_file=""
  # Prefer env outside web/public paths when set
  if [[ -n "${HPCL_DB_BACKUP_ENV:-}" && -f "${HPCL_DB_BACKUP_ENV}" ]]; then
    env_file="${HPCL_DB_BACKUP_ENV}"
  elif [[ -f "${HOME}/private/hpcl_db_backup.env" ]]; then
    env_file="${HOME}/private/hpcl_db_backup.env"
  elif [[ -f "${SCRIPT_DIR}/.env" ]]; then
    env_file="${SCRIPT_DIR}/.env"
  else
    die "Config not found. Create ${SCRIPT_DIR}/.env from .env.example or set HPCL_DB_BACKUP_ENV."
  fi

  set -a
  # shellcheck disable=SC1090
  source "${env_file}"
  set +a
  log "INFO" "Loaded config from ${env_file}"
}

require_cmd() {
  command -v "$1" >/dev/null 2>&1 || die "Required command not found: $1"
}

create_mysql_cnf() {
  TMP_CNF="$(mktemp "${SCRIPT_DIR}/.tmp/mysqldump.XXXXXX.cnf" 2>/dev/null || mktemp)"
  chmod 600 "${TMP_CNF}"
  cat > "${TMP_CNF}" <<EOF
[client]
host=${DB_HOST}
port=${DB_PORT:-3306}
user=${DB_USER}
password=${DB_PASS}
EOF
}

main() {
  mkdir -p "${SCRIPT_DIR}/.tmp" "${SCRIPT_DIR}/db_backups"
  chmod 700 "${SCRIPT_DIR}/.tmp" 2>/dev/null || true

  log "INFO" "===== Backup job started (${DATE_TAG}) ====="
  load_env

  : "${DB_HOST:?DB_HOST is required}"
  : "${DB_NAME:?DB_NAME is required}"
  : "${DB_USER:?DB_USER is required}"
  : "${DB_PASS=?DB_PASS must be set (can be empty for local socket auth)}"

  GIT_REMOTE="${GIT_REMOTE:-origin}"
  GIT_BRANCH="${GIT_BRANCH:-main}"
  RETENTION_DAYS="${RETENTION_DAYS:-14}"
  MYSQLDUMP_BIN="${MYSQLDUMP_BIN:-mysqldump}"

  if [[ -n "${GIT_REPO_ROOT:-}" ]]; then
    REPO_ROOT="$(cd "${GIT_REPO_ROOT}" && pwd)"
  else
    REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
  fi

  require_cmd gzip
  require_cmd git
  if ! command -v "${MYSQLDUMP_BIN}" >/dev/null 2>&1; then
    die "mysqldump not found (${MYSQLDUMP_BIN}). Install MySQL client or set MYSQLDUMP_BIN."
  fi

  if [[ ! -d "${REPO_ROOT}/.git" ]]; then
    die "Git repo not found at ${REPO_ROOT}. Set GIT_REPO_ROOT in .env."
  fi

  DAY_DIR="${SCRIPT_DIR}/db_backups/${DATE_TAG}"
  mkdir -p "${DAY_DIR}"
  chmod 700 "${DAY_DIR}" 2>/dev/null || true

  BACKUP_BASENAME="${DB_NAME}_${DATE_TAG}_${TIME_TAG}.sql"
  BACKUP_SQL="${DAY_DIR}/${BACKUP_BASENAME}"
  BACKUP_GZ="${BACKUP_SQL}.gz"
  LATEST_LINK="${DAY_DIR}/${DB_NAME}_latest.sql.gz"

  create_mysql_cnf

  log "INFO" "Dumping database '${DB_NAME}' to ${BACKUP_SQL}"
  if ! "${MYSQLDUMP_BIN}" \
      --defaults-extra-file="${TMP_CNF}" \
      --single-transaction \
      --quick \
      --routines \
      --triggers \
      --events \
      --hex-blob \
      --default-character-set=utf8mb4 \
      "${DB_NAME}" > "${BACKUP_SQL}"; then
    rm -f "${BACKUP_SQL}"
    die "mysqldump failed for database '${DB_NAME}'"
  fi

  if [[ ! -s "${BACKUP_SQL}" ]]; then
    rm -f "${BACKUP_SQL}"
    die "mysqldump produced an empty file"
  fi

  log "INFO" "Compressing ${BACKUP_SQL}"
  if ! gzip -f "${BACKUP_SQL}"; then
    die "gzip failed for ${BACKUP_SQL}"
  fi

  if [[ ! -f "${BACKUP_GZ}" ]]; then
    die "Compressed backup missing: ${BACKUP_GZ}"
  fi

  ln -sfn "$(basename "${BACKUP_GZ}")" "${LATEST_LINK}" 2>/dev/null || cp -f "${BACKUP_GZ}" "${LATEST_LINK}"
  log "INFO" "Backup ready: ${BACKUP_GZ} ($(du -h "${BACKUP_GZ}" | awk '{print $1}'))"

  # Retention: remove date folders older than RETENTION_DAYS
  if [[ "${RETENTION_DAYS}" =~ ^[0-9]+$ ]] && [[ "${RETENTION_DAYS}" -gt 0 ]]; then
    log "INFO" "Applying retention: keep last ${RETENTION_DAYS} days"
    find "${SCRIPT_DIR}/db_backups" -mindepth 1 -maxdepth 1 -type d -mtime "+${RETENTION_DAYS}" -print | while read -r olddir; do
      log "INFO" "Removing old backup folder ${olddir}"
      rm -rf "${olddir}"
    done
  fi

  cd "${REPO_ROOT}" || die "Cannot cd to repo ${REPO_ROOT}"

  # Stage only backup artifacts under db_backup/
  if ! git add "db_backup/db_backups/${DATE_TAG}" "db_backup/db_backups/.gitkeep" 2>>"${LOG_FILE}"; then
    die "git add failed"
  fi

  if git diff --cached --quiet; then
    log "INFO" "No new backup files to commit (already committed for this run?)"
  else
    COMMIT_MSG="DB backup ${DATE_TAG}"
    if ! git -c user.name="${GIT_USER_NAME:-HPCL Backup Bot}" \
             -c user.email="${GIT_USER_EMAIL:-backup-bot@localhost}" \
             commit -m "${COMMIT_MSG}" >>"${LOG_FILE}" 2>&1; then
      die "git commit failed (see ${LOG_FILE})"
    fi
    log "INFO" "Committed: ${COMMIT_MSG}"

    if ! git push "${GIT_REMOTE}" "${GIT_BRANCH}" >>"${LOG_FILE}" 2>&1; then
      die "git push failed to ${GIT_REMOTE}/${GIT_BRANCH}. Check SSH deploy key / credentials."
    fi
    log "INFO" "Pushed to ${GIT_REMOTE}/${GIT_BRANCH}"
  fi

  log "INFO" "===== Backup job finished successfully ====="
}

main "$@"
exit "${EXIT_CODE}"
