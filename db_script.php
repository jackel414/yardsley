 <?php
$servername = "localhost:8889";
$username = "root";
$password = "root";
$dbname = "yardsley";

try
{
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
    $create_db = "CREATE DATABASE IF NOT EXISTS `yardsley` DEFAULT CHARACTER SET latin1 					COLLATE latin1_swedish_ci;
					USE `yardsley`";
					
    $conn->exec($create_db);
	
    $conn2 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);	

					
	$create_poducts_table = "CREATE TABLE `products` (
							  `id` int(11) NOT NULL,
							  `user_id` int(11) unsigned NOT NULL,
							  `name` varchar(255) NOT NULL,
							  `description` text,
							  `photo` varchar(255) NOT NULL DEFAULT 'generic',
							  `category` varchar(255) NOT NULL,
							  `brand` varchar(255) NOT NULL,
							  `original_price` int(11) NOT NULL,
							  `asking_price` int(11) NOT NULL,
							  `created_at` datetime NOT NULL,
							  `updated_at` datetime NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	$create_users_table = "CREATE TABLE `users` (
							  `id` int(11) NOT NULL,
							  `name` varchar(255) NOT NULL,
							  `email` varchar(255) NOT NULL,
							  `created_at` datetime NOT NULL,
							  `updated_at` datetime NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	
	$add_products_key = "ALTER TABLE `products` ADD PRIMARY KEY (`id`), ADD KEY `products_user_id_foreign` (`user_id`) USING BTREE";
	$add_users_key = "ALTER TABLE `users` ADD PRIMARY KEY (`id`)";
	$auto_increment_product_id = "ALTER TABLE `products` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
	$auto_increment_user_id = "ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
	
    $conn2->exec($create_poducts_table);
    $conn2->exec($create_users_table);
    $conn2->exec($add_products_key);
    $conn2->exec($add_users_key);
    $conn2->exec($auto_increment_product_id);
    $conn2->exec($auto_increment_user_id);
    echo "Database set up successfully";
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

$conn = null;
?> 