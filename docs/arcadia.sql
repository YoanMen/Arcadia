-- création de la table user
CREATE TABLE user(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  locked SMALLINT NOT NULL DEFAULT 0,
  email VARCHAR(60) NOT NULL, 
  password VARCHAR(60) NOT NULL,
  role VARCHAR(20) DEFAULT 'employee' NOT NULL);

-- création d'une table pour comptage de nombres de connexion avec mauvais mot de passe

CREATE TABLE tryConnection (
  user_id INT NOT NULL PRIMARY KEY,
  count INT DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE)

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
  statut VARCHAR(20) NOT NULL,
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
  approved TINYINT NOT NULL DEFAULT 0);

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

-- ajout d'un commentaire sur un habitat

INSERT INTO habitatComment (habitatID, userID, comment)
  VALUE (1, 3, 'L\'habitat est propre, rien à redire.');

-- ajout d'un animal 
INSERT INTO animal (name, race, habitatID) 
  VALUE ('Riki', 'Tigre', 1);


-- ajout d'un rapport de nourrisage
INSERT INTO foodAnimal (userID, animalID, food, quantity, time, date)
  VALUE (2, 1, 'viande', 4.800, "10:20:0", "2024-04-03");


-- ajout des horaire
	
INSERT INTO schedule (day, open, close) 
  VALUE ("lundi", "08:30:00", "18:30:00"),
  ("mardi", "08:30:00", "18:30:00"),
  ("mercredi", "08:30:00", "18:30:00"),
  ("jeudi", "08:30:00", "18:30:00"),
	("vendredi", "08:30:00", "18:30:00"),
  ("samedi", "08:30:00", "18:30:00"),
  ("dimanche", NULL, NULL);

-- ajout d'un rapport sur un animal

INSERT INTO reportAnimal (userID, animalID, statut, food, weight, date, details )
  VALUES (3, 1,'tout va bien','viande', 7, CURRENT_TIMESTAMP(), "Riki à l'air de s'être bien acclimaté à son nouvel enclos, il s'amuse beaucoup aucun signe de préoccupation.");

-- ajout advice

INSERT INTO advice (pseudo, advice, approved )
  VALUES ('martin', 'Ce zoo est une oasis pour les amateurs de faune ! Les nouveaux enclos sont impressionnants et avec un personnel au fait de chaque espèce.', TRUE),
  ('Lisa', "Arcadia est un havre pour les photographes ! Des enclos bien positionnés pour des clichés impeccables, avec un respect maximal pour les animaux.", TRUE), 
  ('martinez',"J'ai bien apprécié ce zoo, il est très bien ! Il y a beaucoup d'espace pour les animaux.", FALSE); 

-- ajout image
INSERT INTO image (path)
  VALUES ("e0ce66feff4b.jpg"),
  ("db3b79c266d0.jpg"),
  ("105922-2689436.jpg");

-- ajout habitat_image
INSERT INTO habitat_image(habitatID,imageID )
  VALUES (1, 1);

-- ajout animal_image
INSERT INTO animal_image(animalID,imageID )
  VALUES (1, 2),
   (1, 3);
  
-- ajout de services
INSERT INTO `service` (`id`, `name`, `description`) 
  VALUES(1, 'Restauration', 'Profitez d&#039;un délicieux repas devant l&#039;enclos de nos éléphants qui s&#039;étend sur une vaste plaine. \r\n\r\nNous vous proposons une cuisine savoureuse et variée, issue des producteurs locaux.'),
  (2, 'Visite des habitats avec un guide', 'Participez à une expérience immersive lors de notre visite des habitats, guidée par nos experts passionnés.\r\n\r\nCette visite gratuite vous permettra de découvrir et d&#039;explorer divers habitats, tout en bénéficiant des connaissances approfondies de nos guides expérimentés.\r\n\r\nPlongez dans la richesse de la nature et apprenez-en davantage sur les écosystèmes fascinants qui entourent chaque habitat. Une aventure éducative et divertissante vous attend, sans frais supplémentaires !'),
  (3, 'Visite du zoo en train', 'Embarquez pour une aventure unique avec notre passionnant voyage à travers le zoo en train. \r\n\r\nProfitez du confort du train tout en explorant les divers habitats et en observant de près nos incroyables animaux. \r\n\r\nNotre guide expert partagera des informations fascinantes sur chaque espèce, vous offrant une expérience éducative et divertissante.\r\n\r\nDétendez-vous et laissez-vous emporter par cette visite inoubliable du zoo, une manière pittoresque et enrichissante de découvrir la diversité de la vie animale, tout cela à bord de notre train exclusif.');
