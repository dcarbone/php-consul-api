#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TMPDIR=${DIR}/../tmp

# consume rest of arguments as agent flags
FLAGS=""
for flag in $@
do
    FLAGS+=" `echo \"${flag}\" | sed 's/"/\"/g'`"
done;

# TODO: maybe some rtrimming?
echo Starting Single consul instance with flags "${FLAGS}" >> ${TMPDIR}/consul.log

# start agent with flags, capturing output into log file
/usr/bin/env consul agent${FLAGS} >> ${TMPDIR}/consul.log 2>&1 & echo $! > ${TMPDIR}/consul.pid
