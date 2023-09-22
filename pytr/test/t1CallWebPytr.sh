#!/bin/sh
#
# POST>
#   tr=100011
#   code=TLKM
#   board=RG
#

curl -d "tr=100011&stock=TLKM&board=RG" -H "Content-Type: application/x-www-form-urlencoded" -X POST http://localhost/json/trproc.php
