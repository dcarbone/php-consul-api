#!/bin/sh

DIR="$(cd "$(dirname "$0")" && pwd)"
TMPDIR="${DIR}/../tmp"

if [ -e "${TMPDIR}/consul.pid" ]; then
    kill -INT "$(cat "${TMPDIR}/consul.pid")"
fi
