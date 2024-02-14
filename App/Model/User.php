<?php
namespace App\Model;

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
}
