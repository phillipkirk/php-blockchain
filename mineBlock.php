<?php

    include 'blockchain.php'; 
    $bc = new Blockchain();

    $bc->get_chain();
    
    $previous_block = $bc->get_previous_block();
    $previous_proof = $previous_block['proof'];
    $proof = $bc->proof_of_work($previous_proof);
    $previous_hash = $bc->hash_block($previous_block);
    $bc->create_block($proof, $previous_hash);
    $current_block = $bc->chain[count($bc->chain) - 1];

    $bc->save_chain();

    header('Content-Type: application/json');
    echo json_encode($current_block);