-- Streetzer, schéma actuel. À jouer en root : sudo mysql < database/schema.sql
DROP DATABASE IF EXISTS streetzer;
CREATE DATABASE streetzer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'streetzer'@'127.0.0.1' IDENTIFIED BY 'streetzer';
GRANT ALL PRIVILEGES ON streetzer.* TO 'streetzer'@'127.0.0.1';
FLUSH PRIVILEGES;

USE streetzer;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(80) NOT NULL,
  prenom VARCHAR(80) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  pseudo VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  ville VARCHAR(120) NOT NULL DEFAULT '',
  vehicule VARCHAR(40) NOT NULL DEFAULT '',
  sexe VARCHAR(20) NOT NULL DEFAULT '',
  marque VARCHAR(120) NOT NULL DEFAULT '',
  naissance VARCHAR(20) NOT NULL DEFAULT '',
  bio TEXT NOT NULL,
  avatar VARCHAR(255) NOT NULL DEFAULT '',
  is_admin TINYINT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  body TEXT NOT NULL,
  image_path VARCHAR(255) NULL,
  youtube_id VARCHAR(16) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (created_at)
) ENGINE=InnoDB;

CREATE TABLE comments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  body TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE likes (
  user_id INT UNSIGNED NOT NULL,
  post_id INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, post_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE friendships (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  requester_id INT UNSIGNED NOT NULL,
  addressee_id INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY one_way (requester_id, addressee_id),
  FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (addressee_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Une conversation, deux membres. hidden = masquée pour cette personne seulement.
-- last_read_id = dernier message vu. Non-lu = id supérieur, écrit par l'autre.
CREATE TABLE conversations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE conversation_members (
  conversation_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  last_read_id INT UNSIGNED NOT NULL DEFAULT 0,
  hidden TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (conversation_id, user_id),
  FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  conversation_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  body TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (conversation_id, id)
) ENGINE=InnoDB;

CREATE TABLE notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  actor_id INT UNSIGNED NOT NULL,
  type VARCHAR(20) NOT NULL,
  post_id INT UNSIGNED NULL,
  seen TINYINT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE marques (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

INSERT INTO marques (nom) VALUES
('Peugeot'),('Renault'),('Citroen'),('BMW'),('Audi'),('Volkswagen'),
('Yamaha'),('Honda'),('Kawasaki'),('Ducati'),('Suzuki');
