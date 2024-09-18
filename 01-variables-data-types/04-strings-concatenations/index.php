<?php

$first_name = "Maria";
$last_name = "Clara";

$full_name = $first_name . " " . $last_name;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo 'Learn PHP From Scratch2' ?></title>
</head>

<body class="bg-gray-100">
    <header class="bg-blue-500 text-white p-4">
        <div class="container mx-auto">
            <h1 class="text 3xl font-semibold">
                <?= 'Learn PHP From Scratch 4' ?>
            </h1>
        </div>    
    </header>   
    <div class="container mx-auto p-4 mt-4">
        <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Output -->
        <pclass="text-xl"><?= "Hello, my name is $full_name."?></p>
        <?php echo ($full_name) ?>
    </div>
    </div>
   

</body>
</html>