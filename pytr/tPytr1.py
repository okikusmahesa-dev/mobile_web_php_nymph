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

def runProcs(noTr) :
    mqIn = MsgMq.Mq(cfgutil.cfg["Hts2PyTrMq"])
    mqOut = MsgMq.Mq(cfgutil.cfg["PyTr2HtsMq"])
    for i in xrange(noTr) :
        seq = i + 1
        #
        cmd = "python tr000104.py %d-%d" % ( int(seq / 1000), int(seq % 1000) )
        #cmd = "trtest00.py %d-%d" % ( int(seq / 1000), int(seq % 1000) )
        print("cmd=(%s)" % (cmd))
        # put input file
        argv = cmd.split(" ")[1:]
        fnIn = htsinc.getInFn2(argv)
        fpIn = open(fnIn, "w")
        fpIn.write("01|jku1231")
        fpIn.close()
        #
        mqIn.put(cmd)
        if waitPyTrSem(seq) == True :
            print("wait resp=(%d)" % (seq))
            msg = mqOut.getWithId(seq)
            print("recv=(%s)" % (msg))
        else :
            print("wait timeout...")

def Usage(argv) :
    print("""
        {0} <num python tr>
    """.format(argv[0]))
    sys.exit(0)

def doMain(argv) :
    if len(argv) == 1 :
        Usage(argv)
    cfgutil.readConfig(".", "config_pytr")
    runProcs(int(argv[1]))

if __name__ == "__main__" :
    doMain(sys.argv)

