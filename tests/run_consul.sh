#!/bin/sh

DIR="$(cd "$(dirname "$0")" && pwd)"
TMPDIR="${DIR}/../tmp"

echo "Starting Single consul instance with flags \"$*\""

/usr/bin/env consul agent "$@" >> "${TMPDIR}/consul.log" 2>&1 & echo $! > "${TMPDIR}/consul.pid"
