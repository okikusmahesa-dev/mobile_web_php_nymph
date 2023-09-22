import os
import sys
import time
import timeit
import pyodbc
import datetime
from datetime import timedelta
import htsinc

env = {}

#
# standard python tr template : 20210121a
#
curDt = time.strftime("%Y%m%d%H%M%S")

def doTr(argv, dbcon) :
    val = {}
    val["userId"] = argv[1]
    
    sql = """
        UPDATE TB_LOGIN_MASTER 
            SET last_ping_time = DATEADD(mi, -2, getdate()) 
            WHERE login_id = '%(userId)s'
        """ % ( val )
    trOut = "1|Success|"
    #print "out=" + str(trOut)
    return trOut

def doMain(dbcon, env, argv) :
    st = timeit.default_timer()
    pass
    et = timeit.default_timer()
    print("doMain>end:%.8f" % (et - st))

def doMain2(dbcon, env, argv) :
    st = timeit.default_timer()
    pass
    et = timeit.default_timer()
    print("doMain2>end:%.8f" % (et - st))

if __name__ == "__main__" :
    #print("start:" + time.strftime("%X"))
    env   = htsinc.getHtsEnv()
    dbcon = htsinc.getDbConn(env)
    #
    doMain2(dbcon, env, sys.argv)

