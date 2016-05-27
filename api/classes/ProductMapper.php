<?php

class ProductMapper
{
	protected $conn;

	public function __construct($db) {
		$this->conn = $db;
	}

	public function getProducts()
	{
		$sql = $this->conn->query("SELECT * FROM products");
		$sql->execute();
		$results = $sql->fetchAll();

		$products = array('products' => array());
		foreach($results as $row) {
			$products['products'][] = $row;
		}
		return $products;
	}

	public function getProductById($id)
	{
		$sql = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();
		$result = $sql->fetch();

		$product = array('product' => $result);

		return $product;		
	}

	public function addProduct($data)
	{
		$sql = $this->conn->prepare("INSERT INTO products (user_id, name, photo, category, brand, original_price, asking_price, created_at, updated_at) VALUES (:user_id, :name, :photo, :category, :brand, :original_price, :asking_price, :created_at, :updated_at)");
		$sql->bindValue(":user_id", $data['user_id']);
		$sql->bindValue(":name", $data['name']);
		$sql->bindValue(":photo", $data['photo']);
		$sql->bindValue(":category", $data['category']);
		$sql->bindValue(":brand", $data['brand']);
		$sql->bindValue(":original_price", $data['original_price']);
		$sql->bindValue(":asking_price", $data['asking_price']);
		$sql->bindValue(":created_at", date("Y-m-d H:i:s"));
		$sql->bindValue(":updated_at", date("Y-m-d H:i:s"));
		$sql->execute();

		$product_sql = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
		$product_sql->bindValue(":id", $this->conn->lastInsertId());
		$product_sql->execute();
		$result = $product_sql->fetch();

		$product = array('product' => $result);

		return $product;
	}
}