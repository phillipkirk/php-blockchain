<?php

    class Blockchain {
        public $chain = array();

        function genesis() {
            $this->chain = array();
            $block = array();
            $block['index'] = 1;
            $block['timestamp'] = date("Y-m-d H:i:s:u");
            $block['proof'] = 1;
            $block['previous_hash'] = '0';
            array_push($this->chain, $block);
          }

        function get_previous_block() {
            return $this->chain[count($this->chain) - 1];
        }

        function proof_of_work($previous_proof) {
            // Miners proof submitted
            $new_proof = 1;

            // Status of proof of work
            $check_proof = FALSE;

            while ($check_proof === FALSE) {
                $hash_operation = hash('sha256', strval($new_proof ** 2 - $previous_proof ** 2));
                if (substr($hash_operation, 0, 4) === '0000') {
                    $check_proof = TRUE;
                } else {
                    $new_proof = $new_proof +1;
                }
            }

            return $new_proof;
        }

        function hash_block($block) {
            return hash('sha256', implode(',', $block));
        }

        function create_block($proof, $previous_hash) {
            $block = array();
            $block['index'] = count($this->chain) + 1;
            $block['timestamp'] = date("Y-m-d H:i:s:u");
            $block['proof'] = $proof;
            $block['previous_hash'] = $previous_hash;
            array_push($this->chain, $block);
        }

        function get_chain() {
            $json_chain = file_get_contents('blockchain.json');
            $this->chain = json_decode($json_chain, true);
        }

        function save_chain() {
            $json_chain = json_encode($this->chain);
            file_put_contents("blockchain.json", $json_chain);
        }
        
        function is_chain_valid() {
            $chain = $this->chain;

            $previous_block = $chain[0];
            $block_index = 1;

            
            $block = $chain[$block_index];

            $return = array();
            $return['validity'] = TRUE;

            while ($block  < count($chain)) {
                if ($block['previous_hash'] != $this->hash_block($previous_block)) {
                    $return['validity'] = FALSE;
                } else {
                    $previous_proof = $previous_block['proof'];
                    $current_proof = $block['proof'];

                    $hash_operation = hash('sha256', strval($current_proof ** 2 - $previous_proof ** 2));

                    if (substr($hash_operation, 0, 4) !== '0000') {
                        $return['validity'] = FALSE;
                    } else {
                        $previous_block = $block;
                        $block_index = $block_index + 1;
                    }
                }
            }
            $return['chain_length'] = count($chain);
            return json_encode($return);
        }
    }