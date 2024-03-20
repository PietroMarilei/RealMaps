#!/bin/bash

# Load Dotenv
set -o allexport
source .env set
+o allexport

echo "[*] Killing existing server on port: $SERVER_PORT"

kill -9 $(lsof -i :"$SERVER_PORT") &>/dev/null | exit 0 

php -S localhost:$SERVER_PORT
