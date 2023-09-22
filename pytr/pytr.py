import os
import sys
import time
import timeit
import multiprocessing
import posix_ipc
import importlib
import cfgutil
import MsgMq
import htsinc
# for pyinstaller
import decimal
import unidecode
import dateutil
import utils
import dateutil.relativedelta
import commands
import collections
#import htspwd
import htsutil
import json
import logging
import operator
import TRMsgQ
import math
import threading
import sysv_ipc
import urllib
import urllib2



bEnd = False
llog = None
dbcon = None
env   = None

def setPyTrSem(retId) :
    global llog
    llog.debug("setPyTrSem0(%08d)" % (retId) )
    ret = False
    semNm = "hts.pytr-%08d" % ( retId )
    sdelay = 1.0
    for i in xrange(10) :
        try :
            sem = posix_ipc.Semaphore(semNm, 0)
            sem.release()
            sem.close()
            ret = True
            break
        except Exception as e:
            """
            time.sleep(sdelay)
            """
            llog.error("semNm=(%s)" % ( semNm ))
            llog.error(e, exc_info=True)
            break
    llog.debug("setPyTrSem1")
    return ret

def checkMain2(trName) :
    #print "---------------"
    #print "modules=" + str(sys.modules)
    dd = eval("dir(sys.modules['%s'])" % (trName))
    if "doMain2" in dd :
        #print("%s has doMain2" % ( trName ))
        return True
    return False

def runMain(trName, argv) :
    global dbcon, env, llog
    if env == None :
        env = htsinc.getHtsEnv()
    if dbcon == None :
        dbcon = htsinc.getDbConn(env)
    mod = sys.modules[trName]
    #
    outFn = htsinc.getOutFn2(argv)
    try :
        llog.debug("outFn=(%s)" % ( outFn ))
        os.unlink(outFn)
    except :
        pass
    #
    llog.debug("dbcon=(%s)" % ( str(dbcon) ))
    _old_argv = sys.argv
    sys.argv = argv
    mod.doMain(dbcon)
    sys.argv = _old_argv

def runMain2(trName, argv) :
    global dbcon, env, llog
    llog.debug(">dbcon=(%s)" % ( str(dbcon) ))
    if env == None :
        env = htsinc.getHtsEnv()
    if dbcon == None :
        llog.debug("create dbcon...")
        dbcon = htsinc.getDbConn(env)
    #
    outFn = htsinc.getOutFn2(argv)
    try :
        llog.debug("outFn=(%s)" % ( outFn ))
        os.unlink(outFn)
    except :
        pass
    #
    llog.debug("dbcon=(%s)" % ( str(dbcon) ))
    mod = sys.modules[trName]
    mod.doMain2(dbcon, env, argv)
    pass

"""
1.source /root/.htsenvrc;/usr/bin/python /home/HTS_V1/python/tr%s.py %d-%d >> /var/tmp/tr%s.log 2>&1"

2.;/usr/bin/python /home/HTS_V1/python/tr%s.py %d-%d >> /var/tmp/tr%s.log 2>&1
python tr999998.py 123-001
"""
def doPyProc(mqOut, msg) :
    global llog, env, dbcon
    llog.debug("msg=(%s)" % ( msg ))
    fld  = msg.split(" ")
    #llog.debug("fld=(%s)" % ( str(fld) ))
    fld2 = fld[2].split("-")
    pyName = fld[1][:-3]
    llog.debug("fld=(%s)" % ( str(fld) ))
    #
    retId = int(fld2[0]) * 1000 + int(fld2[1])
    #
    _old_argv = sys.argv
    _old_stdout = sys.stdout
    #
    argv = [ fld[1], fld[2] ]
    sys.argv = argv
    #
    fnLogTr = "%s/%s.log" % (cfgutil.getLogDir(), fld[1])
    llog.debug("trlog(%s)=%s" % ( fld[1], fnLogTr ))
    flog = open(fnLogTr, "a")
    sys.stdout = flog
    try :
        if pyName not in sys.modules.keys() :
            llog.debug("import>%s" % ( pyName ))
            mod = importlib.import_module(pyName)
            sys.modules[pyName] = mod
            if checkMain2(pyName) == True :
                llog.debug("%s.runMain2(%s)" % ( pyName, str(dbcon) ))
                runMain2(pyName, argv)
            else :
                # old tr already executed
                llog.debug("%s.runMain" % ( pyName ))
                pass
        else :
            if checkMain2(pyName) == True :
                llog.debug("cache>%s.runMain2" % ( pyName ))
                runMain2(pyName, argv)
            else :
                llog.debug("cache>%s.runMain" % ( pyName ))
                runMain(pyName, argv)
    except Exception as e :
        llog.error("Error on exec TR(%s)" % (pyName))
        llog.error(e, exc_info=True)
        pass
    flog.close()
    #
    sys.stdout = _old_stdout
    sys.argv   = _old_argv
    """
    pyFileName = "{0}/python/{1}".format(htsDir, fld[0])
    fp = open(pyFileName, "r")
    pyProg = fp.read()
    llog.error("%s" % (msg))
    try :
        exec(pyProg)
    except Exception as e :
        llog.debug(e, exc_info=True)
    """
    llog.debug("msg=(%s),retId=(%s)" % (msg, retId))
    mqOut.putWithId("OK", retId)
    #
    if setPyTrSem(retId) == False :         # set signal to Server
        llog.debug("Timeout>msg=(%s),retId=(%s)" % (msg, retId))
        return
    #
    pass

def doProc(pNo) :
    global bEnd, env, dbcon
    htsDir = os.getenv("HTS_HOME")
    sys.path.append(".")
    sys.path.append(htsDir + "/python")
    #
    mqIn  = MsgMq.Mq(cfgutil.cfg["Hts2PyTrMq"])
    mqOut = MsgMq.Mq(cfgutil.cfg["PyTr2HtsMq"])
    llog.debug("mqIn=%s" % ( cfgutil.cfg["Hts2PyTrMq"] ))
    #
    env = htsinc.getHtsEnv()
    dbcon = htsinc.getDbConn(env)
    while bEnd == False :
        msg = mqIn.get()
        if len(msg) > 0 :
            doPyProc(mqOut, msg)
        else :
            time.sleep(0.01)

def runProcs(numProcs) :
    for i in xrange(numProcs) :
        pp = multiprocessing.Process(target=doProc, args=(i,))
        pp.start()

def Usage(argv) :
    print("""
    Usage : {0} <num of process>
    Example :
        {0} 10     - 10 processes
    """.format(argv[0]))
    sys.exit(0)

def doMain(argv) :
    global  llog
    if len(argv) == 1 :
        Usage(argv)
    cfgutil.readConfig(".", "config_pytr")
    llog = cfgutil.setLogging("PyTr")
    runProcs(int(argv[1]))

if __name__ == "__main__" :
    doMain(sys.argv)

