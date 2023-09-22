import os
import sys
import time
import socket
import logging
import threading

class LogCmdServer() :
    def __init__(self, logNm, logLevel, nPort=19999) :
        self.nPort = nPort
        self.open()
        pass

    def onRecvUdp(self, msg) :
        print("msg=%s" % ( msg ))
        fld = msg.split("|")
        if fld[0] == "DISP" :
            self.dispLogger()
            return
        logger = logging.getLogger(fld[0])
        try :
            ll     = eval("logging." + fld[1])
            logger.setLevel(ll)
        except Exception as e :
            logger.error(e)
            pass
        pass

    def dispLogger(self) :
        loggers = [logging.getLogger()]
        loggers = loggers + [logging.getLogger(name) for name in logging.root.manager.loggerDict]
        #print("%s" % ( str(loggers) ))
        for ll in loggers :
            print("Logger:(%s)" % ( ll.name ))

    def open(self) :
        sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM, socket.IPPROTO_UDP)
        sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        sock.setsockopt(socket.IPPROTO_IP, socket.IP_MULTICAST_TTL, 32)
        sock.setsockopt(socket.IPPROTO_IP, socket.IP_MULTICAST_LOOP, 1)
        maddr = "224.1.1.1"
        sock.bind((maddr, self.nPort))
        host = socket.gethostbyname(socket.gethostname())
        sock.setsockopt(socket.SOL_IP, socket.IP_MULTICAST_IF, socket.inet_aton(host))
        sock.setsockopt(socket.SOL_IP, socket.IP_ADD_MEMBERSHIP,
        socket.inet_aton(maddr) + socket.inet_aton(host))
        #
        self.sock = sock
        self.ti = threading.Thread(target=LogCmdServer.doRecvLoop, args=(self, self.sock))
        self.ti.daemon = True
        self.ti.start()

    def doRecvLoop(self, sock) :
        while True :
            data, addr = self.sock.recvfrom(1024)
            self.onRecvUdp(data)

def doTestMain() :
    log = LogCmdServer("lTest", logging.DEBUG)
    logger = logging.getLogger("lTest")
    conLog = logging.StreamHandler()
    fhLog = logging.FileHandler("t2log.log")
    logger.addHandler(conLog)
    logger.addHandler(fhLog)
    logger.setLevel(logging.DEBUG)
    while True :
        logger.error("Error...")
        logger.warning("Warning...")
        logger.debug("Debug...")
        logger.critical("Critical...")
        print "---------------"
        time.sleep(5.0)

if __name__ == "__main__" :
    doTestMain()

