#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TMPDIR=${DIR}/../tmp

FLAGS=""

for flag in "$@"
do
    FLAGS+=" ${flag}"
done;

# TODO: maybe some ltrimming?
echo Starting Single consul instance with flags \""${FLAGS}"\" >> "${TMPDIR}"/consul.log

/usr/bin/env consul agent${FLAGS} >> "${TMPDIR}"/consul.log 2>&1 & echo $! > "${TMPDIR}"/consul.pid
