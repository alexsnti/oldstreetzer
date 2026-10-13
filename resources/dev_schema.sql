-- Local schema reconstructed from the queries. Loose sql_mode is set at connect time.
CREATE DATABASE IF NOT EXISTS streetzer CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER IF NOT EXISTS 'streetzer'@'127.0.0.1' IDENTIFIED BY 'streetzer';
GRANT ALL PRIVILEGES ON streetzer.* TO 'streetzer'@'127.0.0.1';
FLUSH PRIVILEGES;

USE streetzer;

CREATE TABLE IF NOT EXISTS utilisateurs (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(80) DEFAULT NULL,
  prenom VARCHAR(80) DEFAULT NULL,
  email VARCHAR(190) DEFAULT NULL,
  pseudo VARCHAR(80) DEFAULT NULL,
  motdepasse VARCHAR(64) DEFAULT NULL,
  ville VARCHAR(120) DEFAULT NULL,
  vehicule VARCHAR(40) DEFAULT NULL,
  sexe VARCHAR(20) DEFAULT NULL,
  marque_vehicule VARCHAR(120) DEFAULT NULL,
  naissance VARCHAR(20) DEFAULT NULL,
  description TEXT,
  fb_id VARCHAR(64) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS posts (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateurs_id INT DEFAULT NULL,
  contenu TEXT,
  datepost VARCHAR(40) DEFAULT NULL,
  rootpic VARCHAR(80) DEFAULT NULL,
  stamp INT DEFAULT NULL,
  type VARCHAR(20) DEFAULT NULL,
  embed TEXT,
  img_id INT DEFAULT NULL,
  public TINYINT DEFAULT 1,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS images (
  id INT NOT NULL AUTO_INCREMENT,
  root VARCHAR(255) DEFAULT NULL,
  posts_id INT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS comments (
  id INT NOT NULL AUTO_INCREMENT,
  posts_id INT DEFAULT NULL,
  utilisateurs_id INT DEFAULT NULL,
  contenu TEXT,
  datepost VARCHAR(40) DEFAULT NULL,
  stamp INT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS amis (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateurs_id_current INT DEFAULT NULL,
  utilisateurs_id_distant INT DEFAULT NULL,
  statut VARCHAR(8) DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS mur_photos (
  idpinned_photos INT NOT NULL AUTO_INCREMENT,
  utilisateurs_id_mur INT DEFAULT NULL,
  posts_id INT DEFAULT NULL,
  PRIMARY KEY (idpinned_photos)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS notifications (
  idnotifications INT NOT NULL AUTO_INCREMENT,
  type VARCHAR(40) DEFAULT NULL,
  date_notif VARCHAR(40) DEFAULT NULL,
  posts_id INT DEFAULT NULL,
  utilisateurs_id INT DEFAULT NULL,
  utilisateurs_id_action INT DEFAULT NULL,
  seen VARCHAR(4) DEFAULT '0',
  PRIMARY KEY (idnotifications)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS conversation (
  idconversation INT NOT NULL AUTO_INCREMENT,
  utilisateurs_id_current INT DEFAULT NULL,
  utilisateurs_id_distant INT DEFAULT NULL,
  link VARCHAR(20) DEFAULT NULL,
  last_message VARCHAR(40) DEFAULT NULL,
  new_message VARCHAR(4) DEFAULT '0',
  PRIMARY KEY (idconversation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS chat_messages (
  idchat_messages INT NOT NULL AUTO_INCREMENT,
  utilisateurs_id_current INT DEFAULT NULL,
  utilisateurs_id_distant INT DEFAULT NULL,
  contenu TEXT,
  stamp VARCHAR(40) DEFAULT NULL,
  datepost VARCHAR(40) DEFAULT NULL,
  new_current VARCHAR(4) DEFAULT '0',
  new_distant VARCHAR(4) DEFAULT '0',
  id_conversations VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (idchat_messages)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS online (
  id INT NOT NULL AUTO_INCREMENT,
  utilisateurs_id INT DEFAULT NULL,
  lastlonline INT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS car (
  id INT NOT NULL AUTO_INCREMENT,
  texture VARCHAR(80) DEFAULT NULL,
  spoiler VARCHAR(80) DEFAULT NULL,
  utilisateurs_id INT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS passreset (
  id INT NOT NULL AUTO_INCREMENT,
  token VARCHAR(80) DEFAULT NULL,
  mail VARCHAR(190) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS admin (
  id INT NOT NULL AUTO_INCREMENT,
  pseudo VARCHAR(80) DEFAULT NULL,
  pass VARCHAR(80) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS marques_auto (
  id INT NOT NULL AUTO_INCREMENT,
  marque VARCHAR(80) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO marques_auto (marque) VALUES
('Peugeot'),('Renault'),('Citroen'),('BMW'),('Audi'),('Volkswagen'),
('Yamaha'),('Honda'),('Kawasaki'),('Ducati'),('Suzuki');
