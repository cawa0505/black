<?php

include_once dirname(__FILE__) . "/imageinfo.php";


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
        while ($this->next != null) {
            $this->next = $this->next->next;
        }

        $this->next = new tier();
        $this->next->keywords = $img['keywords'];
        $this->next->branch_imgs = null;
        $this->next->origin = $img['origin'];
        $this->next->thumb = $img['thumb'];
        $this->next->img_hash = md5($ceiling . $floor . json_encode($img));
        $this->next = $head;
    }
    
    public function insert_branch(Branches $img) {
        $this->next->branch_imgs = $img;
        $this->next->branch_imgs->next = null;
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
        do {
            $this->next = load_next($node);
        } while ($this->next != null);
    }
}

?>