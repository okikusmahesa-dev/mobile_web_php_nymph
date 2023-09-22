import cfgutil

fp = open("tr999998.py", "r")
pyProg = fp.read()

cfgutil.test = "hello..cfgutil.."

for i in xrange(10) :
    cfgutil.test = "hello:%d" % ( i )
    exec(pyProg)

