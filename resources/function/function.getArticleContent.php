<?php
getContentFromUrl("http://www.lexpress.fr/actualite/sport/coupe-du-monde-au-bresil-a-quelle-heure-joueront-les-bleus_1301223.html");
function getContentFromUrl($url){

$sites_html = file_get_contents($url);

$html = new DOMDocument();
@$html->loadHTML($sites_html);
$contentArray =  array();
global $contentArray;

//Get all meta tags and loop through them.
foreach($html->getElementsByTagName('meta') as $meta) {

    //If the property attribute of the meta tag is og:image
    if($meta->getAttribute('property')=='og:image'){ 
        //Assign the value from content attribute to $meta_og_img
        
        $contentArray["image"] = $meta->getAttribute('content');
    }
     //If the property attribute of the meta tag is og:image
    if($meta->getAttribute('property')=='og:description'){ 
        //Assign the value from content attribute to $meta_og_img
       $contentArray["description"] = $meta->getAttribute('content');
    }
     //If the property attribute of the meta tag is og:image
    if($meta->getAttribute('property')=='og:url'){ 
        //Assign the value from content attribute to $meta_og_img
        $contentArray["url"] = $meta->getAttribute('content');
    }
     //If the property attribute of the meta tag is og:image
    if($meta->getAttribute('property')=='og:title'){ 
        //Assign the value from content attribute to $meta_og_img
        $contentArray["title"] = $meta->getAttribute('content');
    }

}
 



}

?>
<div style="width:430px; height: 300px; background: rgba(0,0,0,0.6); color: white;">
	
	<h4><?php echo $contentArray["title"]   ?></h4>
	<img src="<?php echo $contentArray["image"]  ?>">
	<p style="float:right; width: 200px;"><?php echo $contentArray["description"]   ?></p>

</div>