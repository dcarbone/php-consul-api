#!/usr/bin/env bash

${HOME}/bin/consul agent -dev &

PID=$!

echo "consul pid: ${PID}"

echo ${PID} > consul.pid

