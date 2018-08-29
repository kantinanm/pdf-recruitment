# pdf-recruitment
Register-form , read json data and  create fillable PDF Form

# How to use
1. Change variable value in print.php , line 12-14 <br>
	$genaral_data_url="";   //see also json format is <a href="https://github.com/kantinanm/pdf-recruitment/blob/master/general_data.json" target="_blank">general_data.json</a><br>
	$education_data_url=""; //see also json format is <a href="https://github.com/kantinanm/pdf-recruitment/blob/master/education.json" target="_blank">education.json</a> <br> 
	$qry_str ="?".$_GET["p"]; // customize parameter after ? eg. "?staffid=".$_GET["p"] <br>

2. Test url : http://xxxxx/print.php?p=[value]
