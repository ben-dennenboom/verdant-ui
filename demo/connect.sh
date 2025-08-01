#!/bin/bash

CONTAINER_NAME="$1"

docker exec -it "verdant_$CONTAINER_NAME" bash ||
echo "Container '$CONTAINER_NAME' does not exist."
