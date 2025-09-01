<?php
require __DIR__ . '/_proxy.php';

// Simply forward GET request to Node.js backend
node('/user-budget');
