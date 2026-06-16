#!/usr/bin/env bash
set -e

MYSQL_DATA=/home/runner/mysql-data
MYSQL_RUN=/home/runner/mysql-run
MYSQL_SOCK=$MYSQL_RUN/mysql.sock

mkdir -p "$MYSQL_RUN"

# Start MariaDB if not already running
if ! mysql -h 127.0.0.1 -P 3306 -u root --connect-timeout=2 -e "SELECT 1;" >/dev/null 2>&1; then
    echo "[start.sh] Starting MariaDB..."
    mysqld \
        --datadir="$MYSQL_DATA" \
        --socket="$MYSQL_SOCK" \
        --pid-file="$MYSQL_RUN/mysql.pid" \
        --port=3306 \
        --bind-address=127.0.0.1 \
        --skip-grant-tables \
        &
    # Wait until MySQL is ready
    for i in $(seq 1 15); do
        if mysql -h 127.0.0.1 -P 3306 -u root --connect-timeout=2 -e "SELECT 1;" >/dev/null 2>&1; then
            echo "[start.sh] MariaDB ready."
            break
        fi
        sleep 1
    done
    # Create database/table if needed
    mysql -h 127.0.0.1 -P 3306 -u root < /home/runner/workspace/php-app/setup.sql 2>/dev/null || true
else
    echo "[start.sh] MariaDB already running."
fi

echo "[start.sh] Starting PHP server on port 8000..."
exec php -S 0.0.0.0:8000 -t /home/runner/workspace/php-app
