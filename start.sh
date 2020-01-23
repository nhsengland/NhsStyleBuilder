#!/bin/bash
cd ./backend
./node_modules/webpack/bin/webpack.js -w &
node server.js &
cd ../frontend
npm run dev &
