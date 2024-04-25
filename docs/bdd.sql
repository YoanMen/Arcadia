-- création de la table user
CREATE TABLE user(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  email VARCHAR(60) NOT NULL, 
  password VARCHAR(60) NOT NULL,
  role VARCHAR(20) DEFAULT 'employee' NOT NULL);

-- création de la table habitat 

CREATE TABLE habitat (
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  name VARCHAR(60) NOT NULL, 
  description TEXT NOT NULL);

-- création de la table animal 
CREATE TABLE animal(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  name VARCHAR(40) NOT NULL, race VARCHAR(40) NOT NULL, 
  habitatID INT, FOREIGN KEY (habitatID) REFERENCES habitat(id) ON DELETE SET NULL );


-- création table image
CREATE TABLE image(
   id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
   path VARCHAR(40) NOT NULL);

-- création table habitat_image

CREATE TABLE habitat_image(
  habitatID INT NOT NULL, 
  imageID INT NOT NULL PRIMARY KEY,
  FOREIGN KEY (habitatID) REFERENCES habitat(id) ON DELETE CASCADE, 
  FOREIGN KEY (imageID) REFERENCES image(id) ON DELETE CASCADE);

-- création table animal_image

CREATE TABLE animal_image(
  animalID INT NOT NULL, 
  imageID INT NOT NULL PRIMARY KEY,
  FOREIGN KEY (animalID) REFERENCES animal(id) ON DELETE CASCADE, 
  FOREIGN KEY (imageID) REFERENCES image(id) ON DELETE CASCADE);

-- création table habitatComment

CREATE TABLE habitatComment (
  habitatID INT NOT NULL PRIMARY KEY, 
  userID INT, comment TEXT NOT NULL, 
  FOREIGN KEY (habitatID) REFERENCES habitat(id) ON DELETE CASCADE, 
  FOREIGN KEY (userID) REFERENCES user(id) ON DELETE SET NULL);

-- création de foodAnimal

CREATE TABLE foodAnimal(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  userID INT , 
  animalID INT NOT NULL, 
  food VARCHAR(20) NOT NULL, 
  quantity FLOAT NOT NULL,time TIME NOT NULL, 
  date DATE NOT NULL,
  FOREIGN KEY (animalID) REFERENCES animal(id) ON DELETE CASCADE, 	
  FOREIGN KEY (userID) REFERENCES user(id) ON DELETE SET NULL);


-- création table reportAnimal 
CREATE TABLE reportAnimal (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  userID INT, 
  animalID INT NOT NULL, 
  food VARCHAR(20) NOT NULL, 
  weight FLOAT NOT NULL, 
  date DATE NOT NULL, 
  details TEXT, 
  FOREIGN KEY (userID) REFERENCES user(id) ON DELETE SET NULL , 
  FOREIGN KEY (animalID) REFERENCES animal(id) ON DELETE CASCADE);

-- création table advice

CREATE TABLE advice(
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, 
  pseudo VARCHAR(20) NOT NULL, 
  advice TEXT NOT NULL, 
  approved BOOL NOT NULL DEFAULT false);

-- création table service

CREATE TABLE service (
  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
  name VARCHAR(60) NOT NULL, 
  description TEXT NOT NULL);

-- creation table schedule	
CREATE TABLE schedule(id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, day VARCHAR(10) NOT NULL, open TIME, close TIME);

-- hydratation de la bdd

-- ajout d'utilisateurs dans la base de données MDP 1234

  INSERT INTO user (email , password, role) 
  VALUE ( 'admin@arcadia.com', '$2y$10$gHkVx1RYZz7VPwUqb1qv4uMJFYwUKAph1Fp7CIvXC1jGmzgEhxIRi' , 'admin'),
	( 'employee@arcadia.com', '$2y$10$gHkVx1RYZz7VPwUqb1qv4uMJFYwUKAph1Fp7CIvXC1jGmzgEhxIRi' , 'employee'),
	( 'veterinary@arcadia.com', '$2y$10$gHkVx1RYZz7VPwUqb1qv4uMJFYwUKAph1Fp7CIvXC1jGmzgEhxIRi' , 'veterinary');


-- ajout d'un habitat
  INSERT INTO habitat (name, description) 
  VALUES ('Jungle', 'En explorant notre jungle, vous pénétrerez dans un royaume aussi luxuriant que mystérieux, plongeant profondément au cœur de la nature sauvage. Découvrez nos majestueux tigres, imposants alligators, redoutables piranhas et impressionnants gorilles évoluant dans cet écosystème exotique. Chaque pas vous rapprochera de la splendeur captivante de cette jungle, où la vie sauvage prospère dans toute sa diversité. Bienvenue dans une expérience immersive, où la beauté et la majesté de la nature prennent vie sous vos yeux émerveillés.');

-- ajout d'un animal 

INSERT INTO animal (name, race, habitatID) 
  VALUE ('Riki', 'Tigre', 1);

-- ajout des horaire
	
	INSERT INTO schedule (day, open, close) VALUE ("lundi", "08:30:00", "18:30:00"),("mardi", "08:30:00", "18:30:00"),("mercredi", "08:30:00", "18:30:00"),("jeudi", "08:30:00", "18:30:00"),
	("vendredi", "08:30:00", "18:30:00"),("samedi", "08:30:00", "18:30:00"),("dimanche", NULL, NULL);