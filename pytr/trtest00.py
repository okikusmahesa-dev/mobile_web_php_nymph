import os
import sys

def doMain(dbcon) :
    print("END doMain(%s)>sys.argv=(%s)" % (dbcon, sys.argv))
    pass

print("sys.argv=(%s)" % (sys.argv))
doMain(1)
