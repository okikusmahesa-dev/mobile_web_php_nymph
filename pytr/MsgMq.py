import os
import sys
import timeit
import redis
import sysv_ipc
import cfgutil
import rdb

class Mq :
    def __init__(self, qKey) :
        self.qKey = qKey
        if qKey[:3] == "pq:" :
            # python multiprocessing Queue
            # pq:1, pq:2,...
            idx = int(qKey[3:])
            self.qtype = 1
            self.rDb = cfgutil.pq[idx]
        if qKey[:4] == "ipc:" :
            # SycV IPC MQ
            self.qtype = 2
            self.rDb = sysv_ipc.MessageQueue(int(qKey[4:], 0), sysv_ipc.IPC_CREAT, 0o666)
        else :
            self.qtype = 9
            self.rDb = cfgutil.RedisInit(qKey)
        pass

    def put(self, msg) :
        if self.qtype == 9 :
            self.rDb.rpush(self.qKey, msg)
        elif self.qtype == 1 :
            self.rDb.put(msg)
        elif self.qtype == 2 :
            self.rDb.send(msg)
        else :
            self.rDb.put(msg)

    def putWithId(self, msg, retId) :
        if self.qtype == 9 :
            self.rDb.rpush(self.qKey, msg)
        elif self.qtype == 1 :
            self.rDb.put(msg)
        elif self.qtype == 2 :
            self.rDb.send(msg, type=retId)
        else :
            self.rDb.put(msg)

    def get(self) :
        if self.qtype == 9 :
            msg = self.rDb.blpop(self.qKey, 1)[1]
        elif self.qtype == 1 :
            msg = self.rDb.get()
        elif self.qtype == 2 :
            msg = ""
            try :
                msg = self.rDb.receive(False)[0]
            except :
                pass
        else :
            msg = self.rDb.get()
        return msg

    def getWithId(self, retId) :
        if self.qtype == 9 :
            msg = self.rDb.blpop(self.qKey, 1)[1]
        elif self.qtype == 1 :
            msg = self.rDb.get()
        elif self.qtype == 2 :
            msg = ""
            try :
                msg = self.rDb.receive(True, retId)[0]
            except :
                pass
        else :
            msg = self.rDb.get()
        return msg


def doTestMain() :
    mq = FepMq("ipc:0x200000")
    #
    st = timeit.default_timer()
    for i in xrange(3000) :
        mq.put("Hello World!!" * 100)
        msg = mq.get()
    et = timeit.default_timer()
    print "%10.6f>get=(%s)" % ( (et-st), msg )
    # 3.199601
    pass

if __name__ == "__main__" :
    doTestMain()

