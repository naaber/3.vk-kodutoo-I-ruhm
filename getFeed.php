<?php
	
	$page_url = "http://uudisvoog.postimees.ee/";
	$file_name = "cache.txt";
	$data_json = file_get_contents("cache.txt");
	$data = json_decode($data_json);
	
	/* SIIT... */
	$html = new DOMDocument();
	libxml_use_internal_errors(true);
	$html->loadHTML(file_get_contents($page_url));
	$xpath = new DOMXPath($html);
	$results = $xpath->query("//*[@class='TextPrNewsWireSmallTitle']");
	/* ...SIIANI kasutasin suures osas: http://stackoverflow.com/questions/18182857/using-php-dom-document-to-select-html-element-by-its-class-and-get-its-text */
	
	$first_article_url = $page_url.$results->item(0)->getAttribute("href");
	$first_article_id = (int)substr(($first_article_url), -6);
	$first_article_url_without_id = substr(($first_article_url), 0, -6);
	
	/* Ülalpool selekteerisin välja http://uudisvoog.postimees.ee/ kõige hiljutisema artikli aadressi ja id, et saaks selle järgi ülejäänud artiklid välja printida */
	
	$obj = new StdClass();
	$obj-> articles = array();
	
	for($i = $first_article_id; $i > $first_article_id - 50; $i--){
		
		$article_id = (string)($i);
		$article_url = $first_article_url_without_id.$article_id;
	
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
		$var1 = $entries->item(0)->textContent;
		$var2 = str_replace(array("\n", "\t"), '', $var1);
		$var3 = str_replace("\r", ' ', $var2);
		/* ...SIIANI kasutasin suures osas: http://stackoverflow.com/questions/20446598/get-div-content-from-external-website */
		
		array_push($obj->articles, json_encode($var3, JSON_UNESCAPED_UNICODE));
	}
	
	file_put_contents($file_name, json_encode($obj, JSON_UNESCAPED_UNICODE));
	echo json_encode($obj, JSON_UNESCAPED_UNICODE);

?>
