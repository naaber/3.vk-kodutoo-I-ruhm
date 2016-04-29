<?php
	
	$page_url = "http://uudisvoog.postimees.ee/?DATE=20160429&ID=";
	$file_name = "cache.txt";
	$data_json = file_get_contents("cache.txt");
	$data = json_decode($data_json);
	
	$obj = new StdClass();
	$obj-> articles = array();
	
	for($i = 0; $i < 20; $i++){
		
		$article_id = (string)(384160 + $i);
		$article_url = $page_url.$article_id;
	
		/* SIIT... */
		$doc = new DOMDocument();
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->recover = true;
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($article_url);
		$xpath = new DOMXPath($doc);
		$query = "//div[@class='Text11px']";
		$entries = $xpath->query($query);
		$var = $entries->item(0)->textContent;
		/* ...SIIANI kasutasin: http://stackoverflow.com/questions/20446598/get-div-content-from-external-website */
		
		array_push($obj->articles, json_encode($var));
	}
	
	file_put_contents($file_name, json_encode($obj));
	echo json_encode($obj);

	//echo $data_json;
?>
