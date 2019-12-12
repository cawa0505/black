<?php

class ImageInfo {

    public $keywords;
    public $loop_cnt;
    public $origin;
    public $thumb_img;
    public $thumb_dir;
    public $next;
    public $info;
    
    public function new_link()
    {
        $head = $this->next;
        if ($head == null) {
            $head = new tier();
            $head->next = null;
            $head->info = new ImageInfo();
            $this->next = $head;
            return;
        }
        
        while ($head->next != null) {
            $this->load_next($head);
        }

        $head->next = new tier();
        $this->next = $head;
    }

    public function insert_branch(ImageInfo $img)
    {
    
        $this->next->info = $img;
        $this->next->info->next = null;
    }

    public function add_branch_img(ImageInfo $node)
    {
    
        $head = $this->next->info;
        if ($head == null) {
            $head = new LinkedLst();
            $head->next = null;
            $head->info = $node;
            $this->next = $head;
            return;
        }
        while ($head->next != null) {
            $this->load_next($head);
        }

        $head->next = new ImageInfo();
        $head->next->info = $node;
        $this->next = $head;
    }

    public function save_dataset(string $filename)
    {
        file_put_contents($filename, json_encode($this));
    }

    private function load_next(&$node)
    {
        $node = $node->next;
        return $node;
    }

    public function load_dataset(string $filename)
    {
        $file = file_get_contents($filename);
        $node = json_decode($file);
        $tier = new tier();
        foreach ($node as $key => $value){
            $tier->key = $value;
        }
        return $tier;
    }

}