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
            load_next($head);
        }

        $head->next = new tier();
        $head->next->info = $img;
        $this->next = $head;
    }

    public function insert_branch(Branches $img)
    {
        $this->next->info = $img;
        $this->next->info->next = null;
    }

    public function add_branch_img(Branches &$node)
    {
        $png = new PNG();
        $cnode = $png->find_tier($node, 2);

        if ($this->search_imgs($cnode) == 0) {
            return $cnode;
        }

        $head = $this->info;
        if ($head == null) {
            $head = new Branches();
            $head->next = null;
            $head->info = $cnode;
            $this->next = $head;
            return;
        }
        while ($head->next != null) {
            load_next($head);
        }

        $head->next = new Branches();
        $head->next->info = $cnode[0];
        $head->next->info->next = null;
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
            $head->next = load_next($node);
        } while ($this->next != null);
        $this->next = $head;
    }

    public function convImg2Branch(ImageInfo $input)
    {
        $output = new Branches();
        foreach ($input as $key => $value) {
            $output->$key = $value;
        }
        return $output;
    }

    public function convBranch2Img(Branches $input)
    {
        $output = new ImageInfo();
        foreach ($input as $key => $value) {
            $output->$key = $value;
        }
        return $output;
    }

    public function search_imgs(array &$input)
    {
        $bri = $input[2];
        $found = 0;
        foreach (scandir(dirname(__FILE__) . "/../dataset/") as $file) {
            if ($file[0] == '.') {
                continue;
            }
            // Saved file
            $svf = file_get_contents(dirname(__FILE__) . "/../dataset/" . $file);
            if ($bri == $svf) {
                $input[0]->thumb_img = $file;
                $input[2] = "";
                return 1;
            }
        }
        if ($found == 1) {
            return 1;
        } else {
            return 0;
        }

    }

    public function label_search($filename)
    {
        $head = $this->next;
        while ($head != null && $head->thumb_img != $filename) {
            $this->load_next($head);
        }
        return json_encode($head->info[0]->keywords);
    }

}
