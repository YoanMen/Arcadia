<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;

class User extends Model
{
	protected string $table = 'user';

	private int $id;
	private string $email;
	private string $password;

	private string $role;


	/**
	 * Get the value of table
	 */
	public function getTable(): string
	{
		return $this->table;
	}

	/**
	 * Set the value of table
	 */
	public function setTable(string $table): self
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * Get the value of id
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 */
	public function setId(int $id): self
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the value of email
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 */
	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get the value of password
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 */
	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get the value of role
	 */
	public function getRole(): string
	{
		return $this->role;
	}

	/**
	 * Set the value of role
	 */
	public function setRole(string $role): self
	{
		$this->role = $role;

		return $this;
	}

	public function userCount($search)
	{

		try {
			$search .= '%';

			$pdo = $this->connect();
			$query = "SELECT COUNT(user.id) as total_count
								FROM user
								WHERE user.email LIKE :search	";

			$stm = $pdo->prepare($query);
			$stm->bindParam(':search', $search, PDO::PARAM_STR);

			if ($stm->execute()) {
				$result = $stm->fetch();
			}

			return ($result[0] != null) ? $result[0] : null;
		} catch (DatabaseException $e) {
			throw new DatabaseException('Error count :' . $e->getMessage());
		}
	}

	public function fetchUsers(string $search, string $order, string $orderBy): array|null
	{
		try {

			$results = null;

			$search .=  '%';

			$allowedOrderBy = ['id', 'email', 'role'];
			$allowedOrder = ['ASC', 'DESC'];

			$orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
			$order = in_array($order, $allowedOrder) ? $order : 'ASC';


			$pdo = $this->connect();
			$query = "SELECT user.id, user.email, user.password , user.role 
							FROM $this->table
							WHERE user.email LIKE :search
							AND NOT user.role = 'admin'
							ORDER BY $orderBy  $order LIMIT $this->limit OFFSET $this->offset";


			$stm = $pdo->prepare($query);
			$stm->bindParam(':search', $search, PDO::PARAM_STR);

			if ($stm->execute()) {
				while ($result =  $stm->fetch(PDO::FETCH_ASSOC)) {
					$results[] = $result;
				}
			}

			return $results;
		} catch (DatabaseException $e) {
			throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
		}
	}
}
