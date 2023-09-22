function encStr(ss) {
    var dt = new Date();
    var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
    var addTm = dt.getHours().toString() + dt.getMinutes().toString();
    encTime = btoa(time);
    var head = encTime.substring(1, 7);
    strEncHash = head + btoa(addTm + ss);
    return strEncHash;
}

