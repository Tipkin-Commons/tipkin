-- SQL To update crypted password

ALTER TABLE `users` ADD `PASSWORD_PLAIN` VARCHAR( 255 ) NOT NULL AFTER `PASSWORD` ;
UPDATE users SET PASSWORD_PLAIN=PASSWORD;
UPDATE users SET PASSWORD=MD5(CONCAT('mysecretkey', PASSWORD_PLAIN));

--- Vérifier que cela fonctionne puis supprimer la colonne