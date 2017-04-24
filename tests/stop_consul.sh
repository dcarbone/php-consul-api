#!/usr/bin/env bash

if [ -e consul.pid ]; then
    kill -15 $(cat consul.pid)
fi
