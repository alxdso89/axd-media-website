#!/usr/bin/env bash
# Build WordPress upload zips into dist/.
set -euo pipefail
cd "$(dirname "$0")"
rm -rf dist && mkdir -p dist
for dir in axd-media axd-support-desk; do
	zip -rq "dist/${dir}.zip" "$dir" -x '*.DS_Store' '*/node_modules/*'
	echo "Built dist/${dir}.zip ($(du -h "dist/${dir}.zip" | cut -f1))"
done
