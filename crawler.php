<?php

	include("database.php");

	$already_crawled = array();
	$crawling  =array();
	$seed = "$argv[1]";

	function follow_links($url)
	{
		global $already_crawled;
		global $crawling;
		$options = array('http'=>array('method'=>"GET",'headers'=>"User-Agent: testBot/1.0"));

		$context = stream_context_create($options);
		$doc = new DOMDocument();

		@$doc->loadHTML(@file_get_contents($url, false, $context));

		$linklist = $doc -> getElementsByTagName("a");

		
		foreach ($linklist as $link) {
			$l = $link->getAttribute("href");

			if(substr($l,0,1) == "/" && substr($l,0,2) !="//")
				$l = parse_url($url)["scheme"]."://". parse_url($url)["host"].$l;
			elseif(substr($l,0,2) =="//")
				$l = parse_url($url)["scheme"].":".$l;
			elseif(substr($l,0,2) =="./")
				$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].dirname(parse_url($url)["path"]).substr($l,1);
			elseif(substr($l,0,1) =="#")
				$l = parse_url($url)["scheme"]."://".parse_url($url)["host"].parse_url($url)["path"].$l;
			elseif(substr($l,0,3) =="../")
				$l = parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
			elseif(substr($l,0,11) == "javascript:")
				continue;
			elseif(substr($l,0,5) != "https" && substr($l,0,4) !="http")
				$l = parse_url($url)["scheme"].":".parse_url($url)["host"]."://".$l;
			
		
		echo "\n";

		if(!in_array($l, $already_crawled))
			{
				$already_crawled[] = $l;
				$crawling[] = $l;
				$details =  get_details($l);
				insert_into_database($details[0],$details[1],$details[2],$details[3]);
				echo '{ "Title": "'.str_replace("\n", "", $details[0]).'", "Description": "'.str_replace("\n", "", $details[1]).'", "Keywords": "'.str_replace("\n", "", $details[2]).'", "URL": "'.$details[3].'"},';
			}
		}


		array_shift($crawling);

		foreach ($already_crawled as $site)
		{
			follow_links($site);
		}
	}


	function get_details($url)
	{
		$options = array('http'=>array('method'=>"GET",'headers'=>"User-Agent: testBot/1.0\n"));

		$context = stream_context_create($options);

		$doc = new DOMDocument();

		@$doc->loadHTML(@file_get_contents($url, false, $context));

		$title = $doc -> getElementsByTagName("title");
		$title = $title->item(0)->nodeValue;

		$description = "";
		$keywords = "";

		$metas = $doc -> getElementsByTagName("meta");

		for($i =0;$i < $metas->length;$i++)
		{
			$meta = $metas -> item($i);
			if (strtolower($meta -> getAttribute("name"))== "description")
				$description = $meta -> getAttribute("content");

			if (strtolower($meta -> getAttribute("name")) == "keywords")
				$keywords = $meta -> getAttribute("content");
		}
		$details = array($title,$description,$keywords,$url);
		return $details;
	}

	follow_links($seed);

?>