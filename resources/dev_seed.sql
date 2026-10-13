USE streetzer;
SET NAMES utf8;

INSERT INTO utilisateurs (id, nom, prenom, email, pseudo, motdepasse, ville, vehicule, sexe, marque_vehicule, naissance, description) VALUES
(1, 'Martin', 'Alex', 'alex@streetzer.local', 'alex', '2c36f3463ebda6918c5f1a95d32cb34d3af64bd4', 'Lyon', 'voiture', 'male', 'Renault Clio', '12/04/1990', 'Toujours sur la route.'),
(2, 'Bernard', 'Lea', 'lea@streetzer.local', 'lea', '2c36f3463ebda6918c5f1a95d32cb34d3af64bd4', 'Marseille', 'moto', 'female', 'Yamaha MT-07', '03/09/1992', 'Virages et soleil.');

INSERT INTO posts (id, utilisateurs_id, contenu, datepost, rootpic, stamp, type, img_id, public) VALUES
(1, 1, 'Premiere sortie avec la Clio, le col etait vide.', '12/10 - 18:04:00', '356a192b7913b04c54574d18c28d46e6395428ab', 1760300003, 'img', 1, 1),
(2, 2, 'MT-07 au lever du soleil, Marseille est a nous.', '12/10 - 07:12:00', 'da4b9237bacccdf19c0760cab7aec4a8359010b0', 1760300002, 'img', 2, 1),
(3, 1, 'Soiree parking, qui vient ?', '11/10 - 21:30:00', '356a192b7913b04c54574d18c28d46e6395428ab', 1760200001, 'img', 3, 1),
(4, 2, 'Plein fait, prochaine etape la nationale.', '11/10 - 16:02:00', 'da4b9237bacccdf19c0760cab7aec4a8359010b0', 1760200000, 'img', 4, 1);

INSERT INTO images (root, posts_id) VALUES
('/images/356a192b7913b04c54574d18c28d46e6395428ab/post_pic/1.jpg', 1),
('/images/da4b9237bacccdf19c0760cab7aec4a8359010b0/post_pic/2.jpg', 2),
('/images/356a192b7913b04c54574d18c28d46e6395428ab/post_pic/3.jpg', 3),
('/images/da4b9237bacccdf19c0760cab7aec4a8359010b0/post_pic/4.jpg', 4);

INSERT INTO comments (posts_id, utilisateurs_id, contenu, datepost, stamp) VALUES
(1, 2, 'Joli col. Tu etais a quelle heure ?', '12/10 - 18:40:00', 1760301000),
(2, 1, 'La lumiere est folle. On se croise ce week-end ?', '12/10 - 08:01:00', 1760302000);

INSERT INTO amis (utilisateurs_id_current, utilisateurs_id_distant, statut) VALUES (1, 2, '1');

INSERT INTO mur_photos (utilisateurs_id_mur, posts_id) VALUES (1, 2);

INSERT INTO notifications (type, date_notif, posts_id, utilisateurs_id, utilisateurs_id_action, seen) VALUES
('newLike', '12/10 - 19:00:00', 1, 1, 2, '0');

INSERT INTO conversation (idconversation, utilisateurs_id_current, utilisateurs_id_distant, link, last_message, new_message) VALUES
(1, 1, 2, '1', '1760303000', '0'),
(2, 2, 1, '1', '1760303000', '1');

INSERT INTO chat_messages (utilisateurs_id_current, utilisateurs_id_distant, contenu, stamp, datepost, new_current, new_distant, id_conversations) VALUES
(2, 1, 'Salut Alex, tu roules toujours en Clio ?', '1760303000', '19:05:00', '0', '0', '1'),
(1, 2, 'Oui, et toi la MT tient le rythme.', '1760303100', '19:06:00', '0', '0', '1');
