import os
import sys
import timeit
htsDir = os.getenv("HTS_HOME")
sys.path.append(".")
sys.path.append(htsDir + "/python")
import htsinc

fp = open("/var/tmp/tr555-11.in", "wt")
fp.write("0001|jku1111")
fp.close()

sys.argv = [ "tr000103.py", "555-11" ]

st = timeit.default_timer()
pyName = "tr000103"
cmd = "import %s" % (pyName)
exec(cmd)
#
for i in xrange(100) :
    cmd = "env = htsinc.getHtsEnv()"
    exec(cmd)
    cmd = "dbcon = htsinc.getDbConn(env)"
    exec(cmd)
    cmd = "%s.doMain(dbcon)" % ( pyName )
    exec(cmd)
    et = timeit.default_timer()
    print("delay=%.8f" % ( et - st ))
