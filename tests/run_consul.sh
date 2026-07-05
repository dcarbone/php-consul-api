#!/bin/sh

DIR="$(cd "$(dirname "$0")" && pwd)"
TMPDIR="${DIR}/../tmp"
PIDFILE="${TMPDIR}/consul.pid"
LOGFILE="${TMPDIR}/consul.log"

echo "Starting Single consul instance with flags \"$*\""

mkdir -p "${TMPDIR}"

if [ -e "${PIDFILE}" ]; then
    PID="$(cat "${PIDFILE}")"
    if [ -n "${PID}" ] && kill -0 "${PID}" 2>/dev/null; then
        echo "Consul already running with PID ${PID}"
        exit 0
    fi
    rm -f "${PIDFILE}"
fi

/usr/bin/env consul agent "$@" >> "${LOGFILE}" 2>&1 &
echo $! > "${PIDFILE}"
