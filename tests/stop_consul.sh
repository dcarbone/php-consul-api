#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TMPDIR=${DIR}/../tmp

if [ -e "${TMPDIR}"/consul.pid ]; then
    kill -SIGINT "$(cat "${TMPDIR}"/consul.pid)"
fi
