<?php

    include 'blockchain.php'; 
    $bc = new Blockchain();

    $bc->get_chain();

    header('Content-Type: application/json');
    echo json_encode($bc->chain);