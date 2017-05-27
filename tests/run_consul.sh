#!/usr/bin/env bash

/usr/bin/env consul agent -dev >> consul.log 2>&1 & echo $! > consul.pid
