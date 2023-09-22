import os
import sys
import time
import timeit
import multiprocessing
import posix_ipc
import cfgutil
import MsgMq
import htsinc

def waitPyTrSem(retId) :
    print("wait sem:%s" % (retId))
    semNm = "hts.pytr-%08d" % ( retId )
    print("semNm=(%s)" % ( semNm ))
    sem = posix_ipc.Semaphore(semNm, posix_ipc.O_CREAT, 0644, 0)
    try :
        sem.acquire(5)
    except posix_ipc.BusyError as e :
        return False
    except Exception as e :
        print("waitPyTrSem>%s" % (str(e)))
        return False
    finally :
        sem.unlink()
        sem.close()
    return True

def runProc(trNo, seq) :
    seq = int(seq)
    pid  = int(seq / 1000)
    tid  = int(seq % 1000)
    fnId = "%s-%s" % ( pid, tid )
    #
    mqIn = MsgMq.Mq(cfgutil.cfg["Hts2PyTrMq"])
    mqOut = MsgMq.Mq(cfgutil.cfg["PyTr2HtsMq"])
    #
    cmd = "python tr%s.py %s" % ( trNo, fnId )
    #cmd = "trtest00.py %d-%d" % ( int(seq / 1000), int(seq % 1000) )
    print("cmd=(%s)" % (cmd))
    # put input file
    argv = cmd.split(" ")[1:]
    #
    mqIn.put(cmd)
    if waitPyTrSem(seq) == True :
        print("wait resp=(%d)" % (seq))
        msg = mqOut.getWithId(seq)
        print("recv=(%s)" % (msg))
        #fnOut = htsinc.getOutFn2(argv)
        #fpOut = open(fnOut, "w")
        #fpOut.write(msg)
        #fpOut.close()
    else :
        print("wait timeout...")

def Usage(argv) :
    print("""
        {0} <trNo> <fnId>
        - input file name  : /var/tmp/fn<fnId>.in
        - output file name : /var/tmp/fn<fnId>.out
        ex)
            {0} 180000 1234-10
    """.format(argv[0]))
    sys.exit(0)

def doMain(argv) :
    if len(argv) == 1 :
        Usage(argv)
    cfgutil.readConfig(".", "config_pytr")
    runProc(sys.argv[1], sys.argv[2])

if __name__ == "__main__" :
    doMain(sys.argv)

