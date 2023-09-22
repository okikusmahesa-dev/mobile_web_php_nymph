import os
import sys
import time
import logging, logging.handlers
import socket
import importlib
import LogCmd

cfg      = None
fnGlobal = "test.env"
pq       = None

"""
envName : sub config name
     ex) "REAL", "MOCK",...
"""
def readConfig(envName, cfgName) :
    global cfg
    hts_home = os.getenv("HTS_HOME")
    sys.path.append(".")
    sys.path.append(hts_home + "/cfg")
    sys.path.append(hts_home + "/cfg/" + envName )
    logging.error("sys.path=(%s)" % ( str(sys.path) ) )
    cfgName = "%s" % ( cfgName )
    cfgLoad = importlib.import_module(cfgName)
    cfg = cfgLoad.cfg
    return cfgLoad.cfg

def getLogDir() :
    defRoot = os.getenv("HTS_HOME")
    if defRoot == None :
        defRoot = "."
    defGroup = os.getenv("_ENV_CODE_")
    if defGroup == None :
        defGroup = "/SQ"
    else :
        defGroup = "/" + defGroup
    logDir = defRoot + "/trace" + defGroup
    return logDir

#
# ex)
#    setLogging("LogTag")
#
def setLogging(fHeader) :
    global cfg
    t = LogCmd.LogCmdServer("LogSvr", logging.DEBUG)
    # create formatter and add it to the handlers
    FORMAT  = '[%(asctime)-15s][%(clientip)s][%(user)-8s]%(message)s'
    FORMAT1 = "[%(asctime)-15s][%(module)-14s][%(funcName)-14s][%(levelname)-6s]>%(message)s"
    FORMAT2 = "[%(asctime)-15s][%(module)-14s][%(funcName)-14s][%(levelname)-6s)>%(message)s"
    fmtCon = logging.Formatter(FORMAT2)
    fmtLog = logging.Formatter(FORMAT1)

    # create file handler which logs even debug messages
    #
    dtLog = time.strftime("%Y%m%d")
    defLevel = logging.DEBUG
    defCon   = True
    #
    if cfg is not None :
        if cfg.has_key("LogDir") :
            defDir = cfg["LogDir"]
        if cfg.has_key("LogLevel") :
            defLevel = cfg["LogLevel"]
        if cfg.has_key("LogConsole") :
            defCon = cfg["LogConsole"]
    #
    logDir = getLogDir()
    try :
        print "LogDir(%s)=(%s)" % ( defLevel, logDir )
        os.makedirs(logDir)
    except :
        pass
    logFn = logDir + "/" + fHeader + "_" + dtLog + ".log"
    print "LogFile=(%s)" % ( logFn )
    #
    logLevel = defLevel
    #logLevel = logging.ERROR
    #logging.basicConfig(stream=sys.stdout, level=logLevel, format=FORMAT2)
    logging.basicConfig(level=logging.CRITICAL, format=FORMAT2)
    # create logger with 'spam_application'
    logRoot = logging.getLogger()
    #logRoot.name = fHeader
    logRoot.setLevel(logging.CRITICAL)
    #log.setFormatter(formatter)

    # SysLog Handler
    fhLog = logging.FileHandler(logFn)
    fhLog.setFormatter(fmtLog)
    llog = logging.getLogger(fHeader)
    print "(%s)handlers=%d" % ( llog.name, len(llog.handlers) )
    if len(llog.handlers) == 0 :
        llog.propagate = False
        llog.setLevel(logLevel)
        llog.removeHandler(fhLog)
        llog.addHandler(fhLog)

        """
        rsyslogAddr = ('127.0.0.1', 514)
        fhSl = logging.handlers.SysLogHandler(address = rsyslogAddr, socktype=socket.SOCK_DGRAM)
        fhSl.setLevel(logLevel)
        fhSl.setFormatter(fmtLog)
        log.addHandler(fhSl)
        """

        # Console Handler
        if defCon == True :
            fhCon = logging.StreamHandler()
            #fhCon.setLevel(logLevel)
            fhCon.setFormatter(fmtCon)
            llog.addHandler(fhCon)

    """
    mh = logging.handlers.MemoryHandler(
        capacity=1024*100,
        flushLevel=logging.ERROR,
        target=fh
    )
    log.addHandler(mh)
    """

    # create console handler with a higher log level
    #ch = logging.StreamHandler()
    #ch.setLevel(logLevel)
    #ch.setFormatter(formatter)
    #log.addHandler(ch)

    #
    llog.info("%s>>(pid=%s,ppid=%s)", fHeader, os.getpid(), os.getppid())
    llog.info ("%s-INFO>>>", fHeader)
    llog.debug("%s-DEBUG>>>", fHeader)
    llog.error("%s-ERROR>>>", fHeader)
    #
    return llog

def setGlobalEnv(lcfg) :
    global cfg
    cfg = lcfg
    fnDir = "%s/dat/%s/" % (os.getenv("HTS_HOME"), os.getenv("_ENV_CODE_"))
    fp = open(fnDir + fnGlobal, "w")
    fp.write(str(cfg))
    fp.close()

def getGlobalEnv() :
    global cfg
    fnDir = "%s/dat/%s/" % (os.getenv("HTS_HOME"), os.getenv("_ENV_CODE_"))
    fp = open(fnDir + fnGlobal, "r")
    val = fp.read(8192)
    cfg = eval(val)
    print "cfg=(%s)" % ( val )


