<!doctype html>
<html lang="en">
<head>
<meta charset= "utf-8">
<title>Comma Thousands Input</title>
<style>
label, input, button{font-size:1.25em}
</style>
<script>
// insert commas as thousands separators 
function addCommas(n){
    var rx=  /(\d+)(\d{3})/;
	    return String(n).replace(/^\d+/, function(w){
		        while(rx.test(w)){
				            w= w.replace(rx, '$1,$2');
							        }
									        return w;
											    });
												}
												// return integers and decimal numbers from input
												// optionally truncates decimals- does not 'round' input
												function validDigits(n, dec){
												    n= n.replace(/[^\d\.]+/g, '');
													    var ax1= n.indexOf('.'), ax2= -1;
														    if(ax1!= -1){
															        ++ax1;
																	        ax2= n.indexOf('.', ax1);
																			        if(ax2> ax1) n= n.substring(0, ax2);
																					        if(typeof dec=== 'number') n= n.substring(0, ax1+dec);
																							    }
																								    return n;
																									}
																									window.onload= function(){
																									    var n1= document.getElementById('number1'),
																										    n2= document.getElementById('number2');
																											    n1.value=n2.value='';

																												    n1.onkeyup= n1.onchange=n2.onkeyup=n2.onchange= function(e){
																													        e=e|| window.event; 
																															        var who=e.target || e.srcElement,temp;
																																	        if(who.id==='number2')  temp= validDigits(who.value,2); 
																																			        else temp= validDigits(who.value);
																																					        who.value= addCommas(temp);
																																							    }   
																																								    n1.onblur= n2.onblur= function(){
																																									        var temp=parseFloat(validDigits(n1.value)),
																																											        temp2=parseFloat(validDigits(n2.value));
																																													        if(temp)n1.value=addCommas(temp);
																																															        if(temp2)n2.value=addCommas(temp2.toFixed(2));
																																																	    }
																																																		}
																																																		</script>
																																																		</head>
																																																		<body>
																																																		<h1>Input Thousands Commas</h1>
																																																		<div>
																																																		<p>
																																																		<label> Any number <input id="number1" value=""></label>
																																																		<label>2 decimal places <input id="number2" value=""></label>
																																																		</p></div>
																																																		</body>
																																																		</html>
