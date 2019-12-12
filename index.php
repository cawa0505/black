<?php

include_once(dirname(__FILE__) . "php/imageinfo.php");
include_once(dirname(__FILE__) . "php/branches.php");
include_once(dirname(__FILE__) . "php/png.php");
include_once(dirname(__FILE__) . "php/tier.php");

$x = new tier();
$png = new PNG();
$imginfo = new ImageInfo();

$x->load_dataset("save.txt");
//$imginfo = $png->find_tier( dirname(__FILE__) . "/../origin/baselinedesc.png", true);

$branch = new branches();
$branch->origin = dirname(__FILE__) . "/../origin/baselinedesc.png";
$branch->thumb_dir = dirname(__FILE__) . "/../dataset/";
$branch->next = null;

$branch->keywords = array("2", "baseline pic");

//returns array is file unfound
// [0] = branches()
// [1] = filename
// [2] = file contents
$node = $x->add_branch_img($branch);
if (is_array($node))
    $png->create_file($node);
    
echo $x->label_search($node[1]);

$branch = new branches();
$branch->origin = dirname(__FILE__) . "/../origin/done.png";
$branch->thumb_dir = dirname(__FILE__) . "/../dataset/";
$branch->next = null;

$branch->keywords = array("1", "done pic");

$node = $x->add_branch_img($branch);
if (is_array($node))
    $png->create_file($node);

echo $x->label_search($node[1]);
$x->save_dataset("save.txt");

?>