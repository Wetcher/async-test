<?php
/**
 * @var array $body
*/
?>

<html>
<head>
    <title>Test response</title>
</head>
<body>
Request body is: <br>

<pre><?= json_encode($body, JSON_PRETTY_PRINT); ?></pre>
</body>
</html>