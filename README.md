Thanks for looking.

branches.php and imageinfo.php (deprecated) are the linked list extensions

png.php is for creating the thumbnails
    resize_png() to scale and to change color depth
    ImageTrueColorToPalette2() to create image with new depth and scale
    create_file() make new dataset image
    find_tier() resize, recolor, and find categorical fit for image (loop_cnt)
    
tier.php is for searching for the thumbnails
    new_link() creates new noded in linkedlist of thumbnails
    insert_branch() (incomplete) [undefined behavior]
    add_branch_img() creates new thumbnail at end of linkedlist
    save_dataset() saves linkedlist to file
    load_dataset() loads linkedlist from file
    load_next() moves to next node in linkedlist
    convImg2Branch() converts ImageInfo to a Branch object (deprecated)
    convBranch2Img() converts Branch to a ImageInfo object (deprecated)
    search_imgs() searches files for matches
    label_search() get label of picture found

index.php is a example on how to use it.

Some functions are going to be deprecated. I'm still running through this

However, the package is functioning. Thanks again for looks :)