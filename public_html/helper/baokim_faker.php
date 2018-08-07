<?php

echo json_encode([
    'transaction_id' => $_POST['transaction_id'],
    'errorMessage' => 'Nạp thẻ thành công',
    'amount' => 20000,
]);