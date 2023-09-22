import os
import sys
import time
import redis

"""
 0 : default
10 : MsgMq
"""

def RedisInit(cfg) :
    rDb = redis.StrictRedis(
        host = cfg["RedisIp"],
        port = cfg["RedisPort"],
        db   = 0)
    return rDb

def MsgMq(cfg) :
    global rDbMsgMq
    rDb = redis.StrictRedis(
        host = cfg["RedisIp"],
        port = cfg["RedisPort"],
        db   = 10)
    rDbMsgMq = rDb
    return rDb

