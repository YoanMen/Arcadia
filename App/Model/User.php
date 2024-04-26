<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Security;
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
	public function getRoleTranslated(): string
	{
		switch ($this->role) {
			case 'employee':
				return 'employé';
			case 'veterinary':
				return 'vétérinaire';
			default:
				return $this->role;
		}
	}

	/**
	 * Set the value of role
	 */
	public function setRole(string $role): self
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * function to get user count depending search param
	 */
	public function userCount($search)
	{

		try {
			$search .= '%';

			$pdo = $this->connect();
			$query = "SELECT COUNT(user.id) as total_count
								FROM user
								WHERE user.email LIKE :search	AND user.role != 'admin' ";

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

	/**
	 * function to fetch users depending search params
	 */
	public function fetchUsers(string $search, string $orderBy, string $order): array|null
	{
		try {

			$results = null;
			$search .=  '%';

			$allowedOrderBy = ['id', 'email', 'role'];
			$allowedOrder = ['asc', 'desc'];


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
				while ($result =  $stm->fetchObject($this::class)) {
					$results[] = $result;
				}
			}

			return $results;
		} catch (DatabaseException $e) {
			throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
		}
	}

	/**
	 * function to login user
	 * @return bool if user email and password is valid
	 */
	public function login(string $email, string $password): bool
	{
		$user = $this->findOneBy(['email' => $email]);

		if ($user && Security::verifyPassword($password, $user->getPassword())) {
			session_regenerate_id(true);
			$_SESSION['user'] = [
				'id' => $user->getId(),
				'email' => $user->getEmail(),
				'role' => $user->getRole(),
			];

			return true;
		}

		return false;
	}

	/**
	 * function to logout user
	 */
	public function logout()
	{
		$_SESSION = array();

		if (ini_get("session.use_cookies")) {

			$params = session_get_cookie_params();

			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}
	}

	/**
	 * function to create a new service
	 */
	public function createService(string $name, string $description)
	{
		$serviceRepo = new Service();
		$service = $serviceRepo->findOneBy(['name' => $name]);

		if ($service) {
			throw new ValidatorException('un service avec ce nom existe déjà');
		}

		$serviceRepo->insert(['name' => $name, 'description' => $description]);
	}

	/**
	 * function to update a service
	 */
	public function updateService(string $name, string $description, int $id)
	{
		$serviceRepo = new Service();

		$service = $serviceRepo->findOneBy(['name' => $name]);

		if ($service && $service->getId() != $id) {
			throw new ValidatorException('un service avec ce nom existe déjà');
		}

		$serviceRepo->update(['name' => $name, 'description' => $description], $id);
	}

	/**
	 * function to delete service
	 */
	public function deleteService(int $id)
	{
		$serviceRepo = new Service();

		$serviceRepo->delete(['id' => $id]);
	}
}
