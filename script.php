style>
.productImage img{
height: 350px;
width:450px;
border-radius: 5px;
}
.productImage{
margin-bottom: 10px;
}
li{
list-style: none;
}
</style>
<?php
// display primary cat products
global $wpdb;
//get table prefix
$table_prefix = $wpdb->prefix;
?>
<ol>
<?php
//get category id
$getCatId = $wpdb->get_row("SELECT FROM ".$table_prefix.'options '."WHERE option_name = 'get_val_of_catid'");
// echo "data from db ".$getCatId->option_value."<br>";
//get limit
$get_limit = $wpdb->get_row("SELECT FROM ".$table_prefix.'options '."WHERE option_name = 'get_limit_of_products'");
// echo "limit ".$get_limit->option_value."<br>";
//get date
// $get_data_datewise_filter = $wpdb->get_row("SELECT * FROM ".$table_prefix.'options '."WHERE option_name = 'get_limit_of_products'");

$getPosts = $wpdb->get_results("SELECT * FROM ".$table_prefix."postmeta INNER JOIN ".$table_prefix."posts WHERE ".$table_prefix."postmeta.post_id = ".$table_prefix."posts.ID and ".$table_prefix."postmeta.meta_key = '_yoast_wpseo_primary_product_cat' and meta_value = $getCatId->option_value and post_status = 'publish' ORDER BY ".$table_prefix."posts.ID DESC limit 0, $get_limit->option_value");
$total_result_rows = $wpdb->num_rows;
// echo "row count ".$total_result_rows;
if($total_result_rows <= 0) //row count
{
echo "<center> <b>No Results Found</b> </center>";
}
else{

foreach ( $getPosts as $res ) {
echo '<li class="product_title entry-title">';
//get product link
$getProductURL = $wpdb->get_results("SELECT FROM ".$table_prefix."postmeta WHERE post_id = $res->post_id and meta_key = '_product_url'");
foreach ( $getProductURL as $productMeta ) {
?>
<h1 class="product_title entry-title">
<a href="<?php echo $productMeta->meta_value; ?>" target="_blank">
<?php
echo $res->post_title;
?>
</a>
</h1>
<?php
//get image
$getProductId = $wpdb->get_results("SELECT FROM ".$table_prefix."postmeta WHERE post_id = $res->post_id and meta_key = '_thumbnail_id'");
foreach ( $getProductId as $productImageId ) {
// echo "image id ".$productImageId->meta_value;

$getProductImageURL = $wpdb->get_results("SELECT * FROM ".$table_prefix."posts WHERE ID = $productImageId->meta_value and post_type = 'attachment'");
foreach ( $getProductImageURL as $getProductImageURL ) {
// echo "url ".$getProductImageURL->guid;
?>
<center> <div class="productImage">
<img src="<?php echo $getProductImageURL->guid; ?>" style="height: 350px;width: 450px">
</div> </center>
<?php
} //end of nested image url loop

} // end product image

} //end of product url
?>

<p>
<?php
$content = $res->post_excerpt;
$con = strip_shortcodes($content);
$filteredContent = strip_tags( $con );
if(strlen($filteredContent) > 500){
$str = substr($filteredContent, 0, 800) . '<span>.. <a href="'.$res->guid.'"> [Read More] </a> </span>';
echo $str;
}
else{
echo $filteredContent;
}
?>
</p>
<?php
$getYoutubeVdio = $wpdb->get_results("SELECT * FROM ".$table_prefix."postmeta WHERE post_id = $res->post_id and meta_key='youtube_url'");
foreach ( $getYoutubeVdio as $vedioLink ) {
//echo "vdio link ".$vedioLink->meta_value."<br>";
//echo str_replace("https://youtu.be/","https://youtube.com/embed/",$vedioLink->meta_value);
$vurl = $vedioLink->meta_value;
// echo $vurl;

?>
<center> <iframe width="420" height="315" style="margin-top:10px;margin-bottom:10px;"
src="<?php echo str_replace("https://youtu.be/","https://youtube.com/embed/",$vedioLink->meta_value); ?>">
</iframe> </center>
<?php
}
//get feature
//$getFeature = $wpdb->get_results("SELECT * FROM ".$table_prefix."postmeta WHERE post_id = $res->post_id and meta_key='feature'");
// foreach ( $getFeature as $feature ) {
// echo "<textarea style='min-height: 170px;border:0px solid transparent;pointer-events: none;' class='feature'>".$feature->meta_value."</textarea>";
// }

//get related info
$getRl = $wpdb->get_results("SELECT * FROM ".$table_prefix."postmeta WHERE post_id = $res->post_id and meta_key='Related_Info'");
foreach ( $getRl as $relatedInfo ) {
echo "<center> <p class='related-info' style='font-weight:bold; font-size:24px'>".$relatedInfo->meta_value."</p> </center>";
}

//$i++;
?>
</li> <br>
<?php
} //end of main loop
} //end of else
?>
</ol>
