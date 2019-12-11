<?php

include_once dirname(__FILE__) . "/imageinfo.php";
include_once dirname(__FILE__) . "/branches.php";


class tier
{

    public $keywords;
    public $next;
    public $branch_imgs;
    public $origin;
    public $thumb;
    public $img_hash;
    public $loop_cnt;

    public function __construct()
    {
        $this->next = null;
    }

    public function new_link(ImageInfo $img)
    {
    
        $head = $this->next;
        while ($head->next != null) {
            load_next($head);
        }

        $head->next = new tier();
        $head->next->keywords = $img['keywords'];
        $head->next->branch_imgs = null;
        $head->next->origin = $img['origin'];
        $head->next->thumb = $img['thumb'];
        $head->next->img_hash = md5($ceiling . $floor . json_encode($img));
        $this->next = $head;
    }
    
    public function insert_branch(Branches $img) {
        $this->next->branch_imgs = $img;
        $this->next->branch_imgs->next = null;
    }
    
    public function add_branch_img(Branches $node) {
        
        $head = $this->next->branch_img;
        while ($head->next != null) {
            load_next($head);
        }
        
        $this->next->branch_img->next = $node;
        $this->next->branch_img = $head;

    }
    
    public function save_dataset($filename) {
        file_put_contents($filename, json_encode($this));
    
    }
    
    private function load_next(&$node) {
        $node = $node->next;
        return $node;
    }
    
    public function load_dataset($filename) {
        $file = file_get_contents($filename);
        $node = json_decode($file);
        $head = new tier();
        do {
            $head->next = load_next($node);
        } while ($this->next != null);
        $this->next = $head;
    }
}

?>