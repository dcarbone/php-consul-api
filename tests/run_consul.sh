#!/usr/bin/env bash

${HOME}/bin/consul agent -dev &

PID=$!

echo "consul pid: ${PID}"

echo ${PID} > consul.pid
echo "sleeping for 5 seconds to give consul time to set up..."
sleep 5

