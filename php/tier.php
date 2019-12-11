<?php

include_once dirname(__FILE__) . "/imageinfo.php";
include_once dirname(__FILE__) . "/branches.php";
include_once dirname(__FILE__) . "/png.php";

class tier
{

    public $keywords;
    public $next;
    public $branch_imgs;
    public $origin;
    public $thumb;
    public $img_hash;
    public $loop_cnt;
    public $info;

    public function __construct()
    {
        $this->next = null;

    }

    public function new_link(ImageInfo $img)
    {
        
        $head = $this->next;
        if ($head == null) {
            $head = new tier();
            $head->next = null;
            $head->info = $img;
            $this->next = $head;
            return;
        }
        while ($head->next != null) {
            $this->load_next($head);
        }

        $head->next = new tier();
        $head->next->info = $img;
        $this->next = $head;
    }

    public function insert_branch(Branches $img)
    {
        $this->next->info->branch_imgs = $img;
        $this->next->info->branch_imgs->next = null;
    }

    public function add_branch_img(Branches $node)
    {
        $png = new PNG();
        $node = $png->find_tier($node->origin, true);
        
        //$node = $this->convImg2Branch($imginfo);
        if ($this->search_imgs($node) == 0)
            return 0;
        echo "++++";
        
        $head = $this->next->info;
        if ($head == null) {
            $head = new Branches();
            $head->next = null;
            $head->info = $node;
            $this->next = $head;
            return;
        }
        while ($head->next != null) {
            $this->load_next($head);
        }

        $head->next = new Branches();
        $head->next->info = $node;
        $this->next = $head;
    }

    public function save_dataset($filename)
    {
        file_put_contents($filename, json_encode($this));
    }

    private function load_next(&$node)
    {
        $node = $node->next;
        return $node;
    }

    public function load_dataset($filename)
    {
        $file = file_get_contents($filename);
        $node = json_decode($file);
        $head = new tier();
        do {
            $this->load_next($node);
        } while ($this->next != null);
        $this->next = $head;
    }

    public function convImg2Branch(ImageInfo $input) {
        $output = new Branches();
        foreach ($input as $key => $value) {
            $output->$key = $value;
        }
        return $output;
    }

    public function convBranch2Img(Branches $input) {
        $output = new ImageInfo();
        foreach ($input as $key => $value) {
            $output->$key = $value;
        }
        return $output;
    }

    public function search_imgs(Branches &$branch, bool $new_tier = false)
    {
        $imginfo = $this->convBranch2Img($branch);
        $bri = file_get_contents(dirname(__FILE__) . "/../dataset/" . $imginfo->thumb_img);
        $node = null;
        $found = 0;
        foreach (scandir(dirname(__FILE__) . "/../dataset/") as $file) {
            // Saved file
            if ($file[0] == '.')
                continue;
            $svf = file_get_contents(dirname(__FILE__) . "/../dataset/" . $file);
            if ($bri == $svf) {
                //unlink(dirname(__FILE__) . "/../dataset/" . $imginfo->thumb_img);
                echo '*****************';
                $branch->thumb_img = $file;
                $found = 1;
                break;
            }
        }
        if ($found == 1)
            return 1;
        else
            return 0;
    }

}

$x = new tier();
$png = new PNG();
$imginfo = new ImageInfo();

$x->load_dataset("save.txt");

//$x->new_link($imginfo);
$branch = new branches();
$branch->origin = dirname(__FILE__) . "/../origin/baselinedesc.png";
$branch->thumb_dir = dirname(__FILE__) . "/../dataset/";
$branch->next = null;

$branch->keywords = array("1", "baseline pic");

$x->new_link($imginfo);
$x->add_branch_img($branch);

$branch = new branches();
$branch->origin = dirname(__FILE__) . "/../origin/done.png";
$branch->thumb_dir = dirname(__FILE__) . "/../dataset/";
$branch->next = null;
//$x->search_imgs($branch);

$branch->keywords = array("1", "done pic");
//$imginfo = $png->find_tier($branch->origin);

$x->new_link($imginfo);
$x->add_branch_img($branch);

echo json_encode($x);
$x->save_dataset("save.txt");
