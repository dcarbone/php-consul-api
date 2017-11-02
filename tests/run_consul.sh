#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TMPDIR=${DIR}/../tmp

/usr/bin/env consul agent -dev >> ${TMPDIR}/consul.log 2>&1 & echo $! > ${TMPDIR}/consul.pid
