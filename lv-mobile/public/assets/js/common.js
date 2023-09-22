function getToday(fmt) {
	var now = new Date();
	today = now.format(fmt);
	return today;
}

function vol2lot(qty) {
	return qty / 100;
}

function goBack() {
	history.back();
}

function getChgV(p, chg) {
	if (p == "1") return chg;
	if (p == "2") return chg;
	if (p == "3") return chg;
	if (p == "4") return "-" + chg;
	if (p == "5") return "-" + chg;
	return chg;
}

function commify(n) {
	var reg = /(^[+-]?\d+)(\d{3})/;

	while (reg.test(n))
	n = n.replace(reg, '$1' + ',' + '$2');

	return n;
}

function del_comma(input)
{
	var s = 0;
	for(i=0; i<input.length;i++) {
		if(input.charAt(i)==",") {
			s++ ;
		}
	}
	for(i = 0;i < s; i++) {
		input = input.replace(",", "");
	}
	return input;
}

function add_comma(input, num)
{
  
    var output = "";
    var output1 = "";
    var output2 = "";
    var temp1 = IsTrimStr(input);
    //var temp1 = input;

    if(temp1 != "") {
        var temp = fixed_number(temp1);
        i = temp.length ;
        var k = i / 3 ;
        var m = i % 3 ;
        var n= 0;
        if(m==0) {
            for(j=0;j<k-1;j++) {
                output1 += temp.substring(n, j*3+3)+",";
                n=j*3+3;
            }
        } else {
            for(j=0;j<k-1;j++)
            {
                output1 += temp.substring(n, j*3+m)+",";
                n=j*3+m;
            }
        }
        
        output1 += temp.substring(n,temp.length);
        var h = searchDot(temp1);
 
        if(num != "0") {
            output2 += "." ;
        }

        if(h == "")
        {
            for(p=0; p<num; p++)
            {
                output2 += "0" ;
            }
        } else {
            
            var temp2 = decimal_number(temp1,num)+"" ;
          
            temp2 = temp2.substring(1,temp2.length);
           
            output2 = temp2;
            
        }
        output = output1 + output2 ;
        
        
    } else if(temp1 == "") {
        if(num == "0")
        {
            output += "" ;
        }
        else
        {
            output += "0." ;
        }
        for(p=0; p<num; p++)
        {
            output += "0" ;
        }
    }
    return output;
}

function RoundEx(val, pos)
{
	var rtn;
	rtn = Math.round(val * Math.pow(10, Math.abs(pos)-1))
	rtn = rtn / Math.pow(10, Math.abs(pos)-1)
	return rtn;
}

function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function miniCurr(val) {
	val = Number(val);

	if (val > 1000000000000 || val < -1000000000000) {
		val = val / 1000000000000;
		val = sprintf("%.2f TB", val);
	} else if (val > 1000000000 || val < -1000000000) {
		val = val / 1000000000;
		val = sprintf("%.2f B", val);
	} else if (val > 1000000 || val < -1000000) {
		val = val / 1000000;
		val = sprintf("%.2f M", val);
	} else if (val > 1000 || val < -1000) {
		val = val / 1000;
		val = sprintf("%.2f T", val);
	}
	return val;
}

function setColorPriceInfo(val1, val2, form, col1, col2) {
    if (val1 != val2)
        val1 > val2 ? $(form).css('color', '#0000ff') : $(form).css('color', '#ff0000');
    else
        $(form).css('color', '#000000');
}

function convertYYYYMMDD_DDMMYYYY(str, dilm) {
    
    var y = str.substring(0, 4);
    var m = str.substring(4, 6);
    var d = str.substring(6, 8);

    if (dilm == "")
        dilm = "/";
	
    return d + dilm + m + dilm + y;
}    


