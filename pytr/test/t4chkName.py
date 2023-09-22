import os
import sys
import importlib

def checkMain2(trName) :
    #print "---------------"
    print "modules=" + str(sys.modules.keys())
    dd = eval("dir(sys.modules['%s'])" % (trName))
    print(dd)
    if "doMain2" in dd :
        #print("%s has doMain2" % ( trName ))
        return True
    return False

def runMain(pyName) :
    _old_argv = sys.argv
    #
    sys.argv = [ pyName, "1-1" ]
    mod = sys.modules[pyName]
    #
    htsinc =  sys.modules["htsinc"]
    inFn = htsinc.getInFn()
    print("inFn=%s" % ( inFn ))
    #
    mod.doMain(1)
    #
    sys.argv = _old_argv
    
def runMain2(pyName) :
    mod = sys.modules[pyName]
    mod.doMain2(1, 2, 3)
    
def doMain(argv) :
    trName = "tr000103"
    mod = importlib.import_module(trName)
    sys.modules[trName] = mod
    #cmd = "import %s" % ( trName )
    #exec(cmd)
    #
    trName = "tr000104"
    mod = importlib.import_module(trName)
    sys.modules[trName] = mod
    #cmd = "import %s" % ( trName )
    #exec(cmd)
    #sys.modules[trName] = (eval(trName))
    #
    trName = "tr000103"
    if checkMain2(trName) == True :
        print("%s has doMain2" % ( trName ))
        runMain2(trName)
    else :
        print("%s has not doMain2" % ( trName ))
        runMain(trName)
    pass

if __name__ == "__main__" :
    doMain(sys.argv)

