<?php

include_once dirname(__FILE__) . "/imageinfo.php";

class PNG extends ImageInfo
{

    public function find_tier($src, int $new_file = 0)
    {
        list($width, $height, $type, $attr) = getimagesize($src);

        $filename = md5(microtime()) . rand(rand(0, 125), rand(125, 232));

        $this->resize_png($src, "../dataset/" . $filename . "0", $width, $height);

        $exp_first = [];
        $img = imagecreatefrompng("../dataset/" . $filename . "0");
        $exp_second = [];
        $loop_cntr = 0;
        $match = 0;
        $img_scaled = '';
        while ($loop_cntr < 25) {
            $img_before_scale = file_get_contents("../dataset/" . $filename . $loop_cntr);
            $hex = bin2hex($img_before_scale);
            $exp_first = explode("000000", $hex);
            if ($exp_second > 0 && $match = count(array_intersect($exp_second, $exp_first)) < 60) {
                $j = 0;
                // Sure there's matches, but are they congruent?
                $lt = (count($exp_second) <= count($exp_first)) ? count($exp_second) : count($exp_first);
                for ($i = 0; $i < $lt && $j < $match; $i++) {
                    if ($exp_second[$i] == $exp_first[$i]) {
                        $j++;
                    }
                }
                // Perfect!
                if (60 < $j) {
                }

                // We took too much away
                else if ($j <= 60) {
                    // step back one iteration
                    //$loop_cntr--;
                }
                // write to file for good
                $scale = null;
                if (file_exists("../dataset/" . $filename . $loop_cntr)) {
                    $scale = imagecreatefrompng("../dataset/" . $filename . $loop_cntr);
                } else {
                    $scale = imagecreatefrompng($src);
                }

                imagepng($scale, "../dataset/" . $filename . $loop_cntr);
                // delete accumulated files
                $lo = 0;
                $img_scaled = file_get_contents("../dataset/" . $filename . $loop_cntr);
                while ($lo <= $loop_cntr && file_exists("../dataset/" . $filename . $lo)) {
                    if (unlink("../dataset/" . $filename . $lo)) {
                        $lo++;
                    } else {
                        //file_put_contents("../dataset/" . $filename, $img_scaled);
                        break 2;
                    }
                }
                //file_put_contents("../dataset/" . $filename, $img_scaled);
                break;
            }
            // if $exp_first and $exp_second have ~35 point token congruencies
            // it's still matching enough.
            imagescale($scale, $width * 0.20);
            //imagepng($scale, "../dataset/" . $filename . $loop_cntr);
            $img_scaled = file_get_contents("../dataset/" . $filename . $loop_cntr);
            unlink("../dataset/" . $filename . $loop_cntr);
            $hex = bin2hex($img_scaled);
            $exp_second = explode("000000", $img_scaled);
            $loop_cntr++;
        }
        $branch = new Branches();
        $branch->origin = $src;
        $branch->thumb_dir = dirname(__FILE__) . "../dataset/";
        $branch->thumb_img = $filename;
        $branch->loop_cnt = $loop_cntr;
        //unlink("../dataset/" . $filename . $loop_cntr);
        if ($new_file == 1)
            file_put_contents("../dataset/" . $filename, $img_scaled);
        if ($new_file == 2)
            return array($branch, $filename, $img_scaled);
        return $branch;
    }

    public function create_file(array $img_info) {
        file_put_contents("../dataset/" . $img_info[1], $img_info[2]);
        
    }
    
    public function resize_png($src, $dst, $dstw, $dsth)
    {
        list($width, $height, $type, $attr) = getimagesize($src);
        $im = imagecreatefrompng($src);
        $tim = imagecreatetruecolor($dstw, $dsth);
        imagecopyresampled($tim, $im, 0, 0, 0, 0, $dstw, $dsth, $width, $height);
        $tim = $this->ImageTrueColorToPalette2($tim, false, 2);
        imagepng($tim, $dst);
        return 1;
    }

    //zmorris at zsculpt dot com function, a bit completed
    public function ImageTrueColorToPalette2($image, $dither, $ncolors)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $colors_handle = ImageCreateTrueColor($width, $height);
        ImageCopyMerge($colors_handle, $image, 0, 0, 0, 0, $width, $height, 100);
        ImageTrueColorToPalette($image, $dither, $ncolors);
        ImageColorMatch($colors_handle, $image);
        ImageDestroy($colors_handle);
        return $image;
    }

}

//$x = new PNG();

//$x->find_tier("done.png");
