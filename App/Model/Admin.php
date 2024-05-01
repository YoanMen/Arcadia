<?php

namespace App\Model;

use App\Model\CouchDB;
use App\Core\Exception\ValidatorException;
use App\Core\Mail;
use App\Core\Security;
use App\Core\UploadFile;

class Admin extends User
{

	private User $user;
	private Schedule $schedule;
	private Habitat $habitat;
	private Animal $animal;

	public function __construct()
	{
		$this->user = new User();
		$this->schedule = new Schedule();
		$this->habitat = new Habitat();
		$this->animal = new Animal();
	}


	/**
	 * function to create new user
	 */
	public function createUser(string $email, string $password, string $role)
	{

		$user = $this->user->findOneBy(['email' => $email]);

		if ($user) {
			throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
		}

		$password =  Security::hashPassword($password);

		$this->insert(['email' => $email, 'password' => $password, 'role' => $role]);

		$mail = new Mail();
		$mail->sendMailToNewUser($email);
	}

	/**
	 * function to update user
	 */
	public function updateUser(
		int $id,
		string $email,
		string $password,
		string $role,
	) {


		$user = $this->user->findOneBy(['email' => $email]);

		if ($user && $user->getId() != $id) {
			throw new ValidatorException('un utilisateur avec cette adresse existe déjà');
		}

		if ($password != null) {
			$password =  Security::hashPassword($password);
			$this->update(['email' => $email, 'password' => $password, 'role' => $role], $id);
		} else {
			$this->update(['email' => $email,  'role' => $role], $id);
		}
	}

	/**
	 * function to delete user
	 */
	public function deleteUser(int $id)
	{

		$this->delete(['id' => $id]);
	}

	/**
	 * function to update schedules
	 */
	public function updateSchedule(int $id, ?string $open, ?string $close)
	{
		$this->schedule->update(['open' => $open, 'close' => $close], $id);
	}

	/**
	 * function to create habitat
	 */
	public function createHabitat(string $name, string $description)
	{

		$habitat = $this->habitat->findOneBy(['name' => $name]);

		if ($habitat) {
			throw new ValidatorException('un habitat avec ce nom existe déjà');
		}

		// upload file and return path
		$path =  UploadFile::upload();
		$imageRepo = new Image();

		// insert on image table image
		$imageRepo->insert(['path' => $path]);
		// get id of image
		$image = $imageRepo->findOneBy(['path' => $path]);

		// insert new habitat on table
		$this->habitat->insert(['name' => $name, 'description' => $description]);
		$habitat = $this->habitat->findOneBy(['name' => $name]);

		// send habitat_id and image_id to habitat_image
		$this->habitat->insertImage($habitat->getId(), $image->getId());
	}
	/**
	 *  function to update habitat
	 */
	public function updateHabitat(int $id, string $name, string $description)
	{
		$this->habitat->update(['name' => $name, 'description' => $description], $id);
	}

	/**
	 * function to delete habitat
	 */
	public function deleteHabitat(int $id)
	{

		$habitatImages = $this->habitat->fetchImages($id);

		if ($habitatImages) {
			foreach ($habitatImages as $image) {
				UploadFile::remove($image['path']);
			}
		}

		$this->habitat->delete(['id' => $id]);
	}

	/**
	 * function to create animal
	 */
	public function createAnimal(string $name, string $race, int $habitat)
	{

		$animal = $this->animal->findOneBy(['name' => $name]);

		if ($animal) {
			throw new ValidatorException('un animal avec ce nom existe déjà');
		}

		// upload file and return path
		$path =  UploadFile::upload();
		$imageRepo = new Image();

		// insert on image table new image
		$imageRepo->insert(['path' => $path]);
		// get id of image
		$image = $imageRepo->findOneBy(['path' => $path]);

		// insert new animal on table
		$this->animal->insert(['name' => $name, 'race' => $race, 'habitatId' => $habitat]);
		$animal = $this->animal->findOneBy(['name' => $name]);

		// send animal_id and image_id to animal_image
		$this->animal->insertImage($animal->getId(), $image->getId());
	}

	/**
	 * function to update animal
	 */
	public function updateAnimal(string $name, string $race, int $habitat, int $id)
	{
		$animal = $this->animal->findOneBy(['name' => $name]);

		if ($animal && $animal->getId() != $id) {
			throw new ValidatorException('un animal avec ce nom existe déjà');
		}

		$this->animal->update(['name' => $name, 'race' => $race, 'habitatId' => $habitat], $id);
	}

	/**
	 * function to delete animal
	 */
	public function deleteAnimal(int $id)
	{
		$animalImages = $this->animal->fetchImages($id);

		// delete image of animal
		if ($animalImages) {
			foreach ($animalImages as $image) {
				UploadFile::remove($image['path']);
			}
		}

		// delete animal
		$this->animal->delete(['id' => $id]);

		$couchDB = new CouchDB();
		$couchDB->deleteAnimalDocument($id);
	}
}
