import os
import sys
import time
import pyodbc
import datetime
from datetime import timedelta
import htsinc

env = {}

curDt = time.strftime("%Y%m%d%H%M%S")

def doTr(argv, dbcon) :
    val = {}
    val["userId"] = argv[1]
    

    sql = """
        UPDATE TB_LOGIN_MASTER 
            SET last_ping_time = DATEADD(mi, -2, getdate()) 
            WHERE login_id = '%(userId)s'
        """ % ( val )
    """
    cur = dbcon.cursor()
    print "sql=[%s]" % (sql)

    rows = cur.execute(sql)
    """
    
    try:
        trOut = "1|Success|"
    except:
        trOut = "1|Fail|"

    print "out=" + str(trOut)
    return trOut

def doMain(dbcon) :
    trIn = htsinc.getIn()
    trOut = doTr(trIn, dbcon)
    htsinc.putOut(trOut)

print("argv=(%s)" % ( str(sys.argv) ))
print("start:" + time.strftime("%X"))
env = htsinc.getHtsEnv()
#dbcon = htsinc.getDbConn(env)
dbcon = None
#
doMain(dbcon)
print("end>" + time.strftime("%X"))

