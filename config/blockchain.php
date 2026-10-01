<?php

return [
    'chain_id' => env('BLOCKCHAIN_CHAIN_ID', 11155111),
    'network_name' => env('BLOCKCHAIN_NETWORK_NAME', 'Ethereum Sepolia Testnet'),
    'contract_address' => env('BLOCKCHAIN_CONTRACT_ADDRESS', ''),
    'rpc_url' => env('BLOCKCHAIN_RPC_URL', 'https://sepolia.infura.io/v3/'),
];
