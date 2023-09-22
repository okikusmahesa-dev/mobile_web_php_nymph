import trtest00

def a() :
    global b
    if b != None :
        print("b is None")
        return b
    b = 10
    print("b")
a()
trtest00.doMain(1)
a()

