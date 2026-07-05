#!/bin/sh

DIR="$(cd "$(dirname "$0")" && pwd)"
TMPDIR="${DIR}/../tmp"
PIDFILE="${TMPDIR}/consul.pid"

if [ ! -e "${PIDFILE}" ]; then
    exit 0
fi

PID="$(cat "${PIDFILE}")"

if [ -z "${PID}" ]; then
    rm -f "${PIDFILE}"
    exit 0
fi

if ! kill -0 "${PID}" 2>/dev/null; then
    rm -f "${PIDFILE}"
    exit 0
fi

kill -INT "${PID}" 2>/dev/null || true

i=0
while [ "${i}" -lt 50 ]; do
    if ! kill -0 "${PID}" 2>/dev/null; then
        rm -f "${PIDFILE}"
        exit 0
    fi
    i=$((i + 1))
    sleep 0.1
done

kill -TERM "${PID}" 2>/dev/null || true

i=0
while [ "${i}" -lt 50 ]; do
    if ! kill -0 "${PID}" 2>/dev/null; then
        rm -f "${PIDFILE}"
        exit 0
    fi
    i=$((i + 1))
    sleep 0.1
done

kill -KILL "${PID}" 2>/dev/null || true
rm -f "${PIDFILE}"
