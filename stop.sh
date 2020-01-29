#!/bin/bash
pgrep -f 'node server.js' | xargs kill
pgrep -f 'node build/dev-server.js' | xargs kill
pgrep -f 'webpack.js -w' | xargs kill
