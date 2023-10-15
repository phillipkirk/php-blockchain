<?php
    include 'blockchain.php'; 
    $bc = new Blockchain();

    $bc->get_chain();

    if ($bc->chain === null) {
        $bc->genesis();
        echo 'Genesis block created!';
    } else {
        echo 'Genesis block exists!';
    }

    $bc->save_chain();