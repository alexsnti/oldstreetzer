<?php 

$articles = simplexml_load_file('https://news.google.fr/news/feeds?q=tuning&output=rsss');
$c = 0;
$iarray = array();



foreach($articles->channel->item as $key => $value){

	$c++;

	foreach ($articles->channel->item[$c]->link as $key => $value) {
		
		$sites_html = file_get_contents($value);
		$html = new DOMDocument();
		$html->loadHTML($sites_html);
		$contentArray = array();


		foreach($html->getElementsByTagName('meta') as $meta) {

    //If the property attribute of the meta tag is og:image
			if($meta->getAttribute('property')=='og:image'){ 
        //Assign the value from content attribute to $meta_og_img
	

				$image = $meta->getAttribute('content');
        /// $iarray[] = array('image' => $meta->getAttribute('content'));
			}
     //If the property attribute of the meta tag is og:image
			if($meta->getAttribute('property')=='og:description'){ 
        //Assign the value from content attribute to $meta_og_img
				
				$description = $meta->getAttribute('content');
				
			}
     //If the property attribute of the meta tag is og:image
			if($meta->getAttribute('property')=='og:url'){ 
        //Assign the value from content attribute to $meta_og_img

				$url = $meta->getAttribute('content');
			}
     //If the property attribute of the meta tag is og:image
			if($meta->getAttribute('property')=='og:title'){ 
        //Assign the value from content attribute to $meta_og_img

				$title = $meta->getAttribute('content');
			}


			
			

		}
	//var_dump($iarray)
	
	}
	
	$iarray[] = array('image'=>$image,'description'=>$description,'url'=>$url,'title'=>$title);
}

echo json_encode($iarray);



?>

