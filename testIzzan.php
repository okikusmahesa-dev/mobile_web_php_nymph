
<?php
		include "https://code.jquery.com/jquery-1.11.1.min.js";
				
				        $json = file_get_contents('https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quotes%20where%20symbol%20in%20(%22DOW,^GSPC,NDAQ,^FTSE,CAC,DAX%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=');
						        $obj = json_decode($json);

								        //print_r($obj);
										        echo 'count : ', $obj->query->count,'<br>' ;
												        echo 'created : ', $obj->query->created,'<br>' ;
														        echo 'lang : ', $obj->query->lang,'<br><br>' ;

																            for($i=0;$i<6;$i++){
																			                echo 'STOCK   : ',$obj->query->results->quote[$i]->symbol,'<br>
																												   Name   : ',$obj->query->results->quote[$i]->Name,'<br>
																												   					   Change : ',$obj->query->results->quote[$i]->Change,'<br>
																																	   					   ChgPercent : ',$obj->query->results->quote[$i]->ChangeinPercent,'<br>
																																						   					   PrevClose  : ',$obj->query->results->quote[$i]->PreviousClose,'<br><br>';
																																											                   }


																																															   ?>
