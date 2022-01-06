#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
TMPDIR=${DIR}/../tmp

consul_args=("$@")

echo Starting Single consul instance with flags \""${consul_args[*]}"\"

/usr/bin/env consul agent "${consul_args[@]}" & echo $! > "${TMPDIR}"/consul.pid
