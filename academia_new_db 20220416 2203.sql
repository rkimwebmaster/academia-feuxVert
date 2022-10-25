﻿--
-- Script was generated by Devart dbForge Studio 2019 for MySQL, Version 8.2.23.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 4/16/2022 10:03:04 PM
-- Server version: 8.0.18
-- Client version: 4.1
--

-- 
-- Disable foreign keys
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Set SQL mode
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Set character set the client will use to send SQL statements to the server
--
SET NAMES 'utf8';

--
-- Set default database
--
USE academia_new_db;

--
-- Drop table `ligne_planification_enseignant`
--
DROP TABLE IF EXISTS ligne_planification_enseignant;

--
-- Drop table `ligne_planification`
--
DROP TABLE IF EXISTS ligne_planification;

--
-- Drop table `planification`
--
DROP TABLE IF EXISTS planification;

--
-- Drop table `salle`
--
DROP TABLE IF EXISTS salle;

--
-- Drop table `depot`
--
DROP TABLE IF EXISTS depot;

--
-- Drop table `ligne_cloture`
--
DROP TABLE IF EXISTS ligne_cloture;

--
-- Drop table `paiement_depot`
--
DROP TABLE IF EXISTS paiement_depot;

--
-- Drop table `proposition`
--
DROP TABLE IF EXISTS proposition;

--
-- Drop table `fiche`
--
DROP TABLE IF EXISTS fiche;

--
-- Drop table `message`
--
DROP TABLE IF EXISTS message;

--
-- Drop table `broadcast_message`
--
DROP TABLE IF EXISTS broadcast_message;

--
-- Drop table `cloture`
--
DROP TABLE IF EXISTS cloture;

--
-- Drop table `reset_password_request`
--
DROP TABLE IF EXISTS reset_password_request;

--
-- Drop table `user`
--
DROP TABLE IF EXISTS user;

--
-- Drop table `enseignant`
--
DROP TABLE IF EXISTS enseignant;

--
-- Drop table `finaliste`
--
DROP TABLE IF EXISTS finaliste;

--
-- Drop table `identite`
--
DROP TABLE IF EXISTS identite;

--
-- Drop table `entreprise`
--
DROP TABLE IF EXISTS entreprise;

--
-- Drop table `promotion`
--
DROP TABLE IF EXISTS promotion;

--
-- Drop table `annee_academique`
--
DROP TABLE IF EXISTS annee_academique;

--
-- Drop table `departement`
--
DROP TABLE IF EXISTS departement;

--
-- Drop table `faculte`
--
DROP TABLE IF EXISTS faculte;

--
-- Set default database
--
USE academia_new_db;

--
-- Create table `faculte`
--
CREATE TABLE faculte (
  id INT(11) NOT NULL AUTO_INCREMENT,
  designation VARCHAR(255) NOT NULL,
  description VARCHAR(255) NOT NULL,
  nombre_fiche_atraiter INT(11) NOT NULL,
  nombre_fiche_cree INT(11) NOT NULL,
  nombre_fiche_soumis INT(11) NOT NULL,
  nombre_fiche_validee INT(11) NOT NULL,
  nombre_fiche_feux_vert INT(11) NOT NULL,
  nombre_fiche_alignee INT(11) NOT NULL,
  nombre_fiche_defendu INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create table `departement`
--
CREATE TABLE departement (
  id INT(11) NOT NULL AUTO_INCREMENT,
  faculte_id INT(11) NOT NULL,
  designation VARCHAR(255) NOT NULL,
  description VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_C1765B6372C3434F` on table `departement`
--
ALTER TABLE departement 
  ADD INDEX IDX_C1765B6372C3434F(faculte_id);

--
-- Create index `UNIQ_C1765B638947610D` on table `departement`
--
ALTER TABLE departement 
  ADD UNIQUE INDEX UNIQ_C1765B638947610D(designation);

--
-- Create foreign key
--
ALTER TABLE departement 
  ADD CONSTRAINT FK_C1765B6372C3434F FOREIGN KEY (faculte_id)
    REFERENCES faculte(id);

--
-- Create table `annee_academique`
--
CREATE TABLE annee_academique (
  id INT(11) NOT NULL AUTO_INCREMENT,
  debut INT(11) NOT NULL,
  fin INT(11) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  is_current TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `UNIQ_A6971EA8AD2EF231` on table `annee_academique`
--
ALTER TABLE annee_academique 
  ADD UNIQUE INDEX UNIQ_A6971EA8AD2EF231(fin);

--
-- Create index `UNIQ_A6971EA8E81B0679` on table `annee_academique`
--
ALTER TABLE annee_academique 
  ADD UNIQUE INDEX UNIQ_A6971EA8E81B0679(debut);

--
-- Create table `promotion`
--
CREATE TABLE promotion (
  id INT(11) NOT NULL AUTO_INCREMENT,
  departement_id INT(11) NOT NULL,
  annee_academique_id INT(11) NOT NULL,
  faculte_id INT(11) NOT NULL,
  designation VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_C11D7DD172C3434F` on table `promotion`
--
ALTER TABLE promotion 
  ADD INDEX IDX_C11D7DD172C3434F(faculte_id);

--
-- Create index `IDX_C11D7DD1B00F076` on table `promotion`
--
ALTER TABLE promotion 
  ADD INDEX IDX_C11D7DD1B00F076(annee_academique_id);

--
-- Create index `IDX_C11D7DD1CCF9E01E` on table `promotion`
--
ALTER TABLE promotion 
  ADD INDEX IDX_C11D7DD1CCF9E01E(departement_id);

--
-- Create foreign key
--
ALTER TABLE promotion 
  ADD CONSTRAINT FK_C11D7DD172C3434F FOREIGN KEY (faculte_id)
    REFERENCES faculte(id);

--
-- Create foreign key
--
ALTER TABLE promotion 
  ADD CONSTRAINT FK_C11D7DD1B00F076 FOREIGN KEY (annee_academique_id)
    REFERENCES annee_academique(id);

--
-- Create foreign key
--
ALTER TABLE promotion 
  ADD CONSTRAINT FK_C11D7DD1CCF9E01E FOREIGN KEY (departement_id)
    REFERENCES departement(id);

--
-- Create table `entreprise`
--
CREATE TABLE entreprise (
  id INT(11) NOT NULL AUTO_INCREMENT,
  annee_academique_courante_id INT(11) NOT NULL,
  nom VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  ville VARCHAR(255) NOT NULL,
  pays VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telephone1 VARCHAR(255) NOT NULL,
  telephone2 VARCHAR(255) DEFAULT NULL,
  website VARCHAR(255) NOT NULL,
  is_publique TINYINT(1) NOT NULL,
  sigle VARCHAR(255) NOT NULL,
  logo VARCHAR(255) NOT NULL,
  devise VARCHAR(255) NOT NULL,
  boite_postale VARCHAR(10) DEFAULT NULL,
  date_fin_proposition_sujet DATE NOT NULL,
  date_debut_defense_session1 DATE NOT NULL,
  date_debut_defense_session2 DATE NOT NULL,
  date_collation_grade DATE NOT NULL,
  prix_depot DOUBLE NOT NULL,
  monaie VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_D19FA6038878D1B` on table `entreprise`
--
ALTER TABLE entreprise 
  ADD INDEX IDX_D19FA6038878D1B(annee_academique_courante_id);

--
-- Create foreign key
--
ALTER TABLE entreprise 
  ADD CONSTRAINT FK_D19FA6038878D1B FOREIGN KEY (annee_academique_courante_id)
    REFERENCES annee_academique(id);

--
-- Create table `identite`
--
CREATE TABLE identite (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  postnom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create table `finaliste`
--
CREATE TABLE finaliste (
  id INT(11) NOT NULL AUTO_INCREMENT,
  identite_id INT(11) NOT NULL,
  promotion_id INT(11) NOT NULL,
  faculte_id INT(11) DEFAULT NULL,
  nombre_correction_directeur INT(11) NOT NULL,
  civilite VARCHAR(255) NOT NULL,
  matricule VARCHAR(255) DEFAULT NULL,
  compteur VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 7,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_242561BE139DF194` on table `finaliste`
--
ALTER TABLE finaliste 
  ADD INDEX IDX_242561BE139DF194(promotion_id);

--
-- Create index `IDX_242561BE72C3434F` on table `finaliste`
--
ALTER TABLE finaliste 
  ADD INDEX IDX_242561BE72C3434F(faculte_id);

--
-- Create index `UNIQ_242561BEE5F13C8F` on table `finaliste`
--
ALTER TABLE finaliste 
  ADD UNIQUE INDEX UNIQ_242561BEE5F13C8F(identite_id);

--
-- Create foreign key
--
ALTER TABLE finaliste 
  ADD CONSTRAINT FK_242561BE139DF194 FOREIGN KEY (promotion_id)
    REFERENCES promotion(id);

--
-- Create foreign key
--
ALTER TABLE finaliste 
  ADD CONSTRAINT FK_242561BE72C3434F FOREIGN KEY (faculte_id)
    REFERENCES faculte(id);

--
-- Create foreign key
--
ALTER TABLE finaliste 
  ADD CONSTRAINT FK_242561BEE5F13C8F FOREIGN KEY (identite_id)
    REFERENCES identite(id);

--
-- Create table `enseignant`
--
CREATE TABLE enseignant (
  id INT(11) NOT NULL AUTO_INCREMENT,
  identite_id INT(11) NOT NULL,
  is_cordirecteur TINYINT(1) NOT NULL,
  nombre_direction INT(11) DEFAULT NULL,
  nombre_feux_vert INT(11) DEFAULT NULL,
  nombre_nouveau_depot INT(11) NOT NULL,
  adresse_bureau VARCHAR(255) NOT NULL,
  grade VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `UNIQ_81A72FA1E5F13C8F` on table `enseignant`
--
ALTER TABLE enseignant 
  ADD UNIQUE INDEX UNIQ_81A72FA1E5F13C8F(identite_id);

--
-- Create foreign key
--
ALTER TABLE enseignant 
  ADD CONSTRAINT FK_81A72FA1E5F13C8F FOREIGN KEY (identite_id)
    REFERENCES identite(id);

--
-- Create table `user`
--
CREATE TABLE user (
  id INT(11) NOT NULL AUTO_INCREMENT,
  faculte_id INT(11) DEFAULT NULL,
  finaliste_id INT(11) DEFAULT NULL,
  enseignant_id INT(11) DEFAULT NULL,
  username VARCHAR(180) NOT NULL,
  roles JSON NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  nombre_message_non_lu INT(11) NOT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 12,
AVG_ROW_LENGTH = 2048,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_8D93D64972C3434F` on table `user`
--
ALTER TABLE user 
  ADD INDEX IDX_8D93D64972C3434F(faculte_id);

--
-- Create index `UNIQ_8D93D6499E6398AF` on table `user`
--
ALTER TABLE user 
  ADD UNIQUE INDEX UNIQ_8D93D6499E6398AF(finaliste_id);

--
-- Create index `UNIQ_8D93D649E455FCC0` on table `user`
--
ALTER TABLE user 
  ADD UNIQUE INDEX UNIQ_8D93D649E455FCC0(enseignant_id);

--
-- Create index `UNIQ_8D93D649E7927C74` on table `user`
--
ALTER TABLE user 
  ADD UNIQUE INDEX UNIQ_8D93D649E7927C74(email);

--
-- Create foreign key
--
ALTER TABLE user 
  ADD CONSTRAINT FK_8D93D64972C3434F FOREIGN KEY (faculte_id)
    REFERENCES faculte(id);

--
-- Create foreign key
--
ALTER TABLE user 
  ADD CONSTRAINT FK_8D93D6499E6398AF FOREIGN KEY (finaliste_id)
    REFERENCES finaliste(id);

--
-- Create foreign key
--
ALTER TABLE user 
  ADD CONSTRAINT FK_8D93D649E455FCC0 FOREIGN KEY (enseignant_id)
    REFERENCES enseignant(id);

--
-- Create table `reset_password_request`
--
CREATE TABLE reset_password_request (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  selector VARCHAR(20) NOT NULL,
  hashed_token VARCHAR(100) NOT NULL,
  requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_7CE748AA76ED395` on table `reset_password_request`
--
ALTER TABLE reset_password_request 
  ADD INDEX IDX_7CE748AA76ED395(user_id);

--
-- Create foreign key
--
ALTER TABLE reset_password_request 
  ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id)
    REFERENCES user(id);

--
-- Create table `cloture`
--
CREATE TABLE cloture (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  date DATE NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_D5D0B568A76ED395` on table `cloture`
--
ALTER TABLE cloture 
  ADD INDEX IDX_D5D0B568A76ED395(user_id);

--
-- Create foreign key
--
ALTER TABLE cloture 
  ADD CONSTRAINT FK_D5D0B568A76ED395 FOREIGN KEY (user_id)
    REFERENCES user(id);

--
-- Create table `broadcast_message`
--
CREATE TABLE broadcast_message (
  id INT(11) NOT NULL AUTO_INCREMENT,
  expediteur_id INT(11) NOT NULL,
  titre VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  groupe_destinataire VARCHAR(255) NOT NULL,
  contenu LONGTEXT NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_26AEADCE10335F61` on table `broadcast_message`
--
ALTER TABLE broadcast_message 
  ADD INDEX IDX_26AEADCE10335F61(expediteur_id);

--
-- Create foreign key
--
ALTER TABLE broadcast_message 
  ADD CONSTRAINT FK_26AEADCE10335F61 FOREIGN KEY (expediteur_id)
    REFERENCES user(id);

--
-- Create table `message`
--
CREATE TABLE message (
  id INT(11) NOT NULL AUTO_INCREMENT,
  broadcast_message_id INT(11) NOT NULL,
  user_receiver_id INT(11) NOT NULL,
  created_at DATETIME NOT NULL,
  is_non_lu TINYINT(1) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_B6BD307F64482423` on table `message`
--
ALTER TABLE message 
  ADD INDEX IDX_B6BD307F64482423(user_receiver_id);

--
-- Create index `IDX_B6BD307FD7B73217` on table `message`
--
ALTER TABLE message 
  ADD INDEX IDX_B6BD307FD7B73217(broadcast_message_id);

--
-- Create foreign key
--
ALTER TABLE message 
  ADD CONSTRAINT FK_B6BD307F64482423 FOREIGN KEY (user_receiver_id)
    REFERENCES user(id);

--
-- Create foreign key
--
ALTER TABLE message 
  ADD CONSTRAINT FK_B6BD307FD7B73217 FOREIGN KEY (broadcast_message_id)
    REFERENCES broadcast_message(id);

--
-- Create table `fiche`
--
CREATE TABLE fiche (
  id INT(11) NOT NULL AUTO_INCREMENT,
  directeur_propose_id INT(11) NOT NULL,
  directeur_retenu_id INT(11) DEFAULT NULL,
  codirecteur_id INT(11) DEFAULT NULL,
  finaliste_id INT(11) NOT NULL,
  promotion_id INT(11) NOT NULL,
  faculte_id INT(11) NOT NULL,
  annee_academique_id INT(11) NOT NULL,
  date DATE NOT NULL,
  numero VARCHAR(255) NOT NULL,
  is_validee TINYINT(1) NOT NULL,
  is_soumis TINYINT(1) NOT NULL,
  observation LONGTEXT DEFAULT NULL,
  etat_fiche INT(11) DEFAULT NULL,
  is_feux_vert TINYINT(1) DEFAULT NULL,
  date_affectation DATETIME DEFAULT NULL,
  sujet_retenu LONGTEXT DEFAULT NULL,
  date_validation DATE DEFAULT NULL,
  is_planifiee TINYINT(1) NOT NULL,
  date_defense DATETIME DEFAULT NULL,
  date_soumission DATE DEFAULT NULL,
  date_feux_vert DATETIME DEFAULT NULL,
  is_paiement_depot TINYINT(1) NOT NULL,
  is_defendue TINYINT(1) NOT NULL,
  is_rejete TINYINT(1) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_4C13CC78139DF194` on table `fiche`
--
ALTER TABLE fiche 
  ADD INDEX IDX_4C13CC78139DF194(promotion_id);

--
-- Create index `IDX_4C13CC7872C3434F` on table `fiche`
--
ALTER TABLE fiche 
  ADD INDEX IDX_4C13CC7872C3434F(faculte_id);

--
-- Create index `IDX_4C13CC789B7F64D9` on table `fiche`
--
ALTER TABLE fiche 
  ADD INDEX IDX_4C13CC789B7F64D9(codirecteur_id);

--
-- Create index `IDX_4C13CC789FA86DF5` on table `fiche`
--
ALTER TABLE fiche 
  ADD INDEX IDX_4C13CC789FA86DF5(directeur_propose_id);

--
-- Create index `IDX_4C13CC78B00F076` on table `fiche`
--
ALTER TABLE fiche 
  ADD INDEX IDX_4C13CC78B00F076(annee_academique_id);

--
-- Create index `IDX_4C13CC78B0F3904E` on table `fiche`
--
ALTER TABLE fiche 
  ADD INDEX IDX_4C13CC78B0F3904E(directeur_retenu_id);

--
-- Create index `UNIQ_4C13CC789E6398AF` on table `fiche`
--
ALTER TABLE fiche 
  ADD UNIQUE INDEX UNIQ_4C13CC789E6398AF(finaliste_id);

--
-- Create foreign key
--
ALTER TABLE fiche 
  ADD CONSTRAINT FK_4C13CC78139DF194 FOREIGN KEY (promotion_id)
    REFERENCES promotion(id);

--
-- Create foreign key
--
ALTER TABLE fiche 
  ADD CONSTRAINT FK_4C13CC7872C3434F FOREIGN KEY (faculte_id)
    REFERENCES faculte(id);

--
-- Create foreign key
--
ALTER TABLE fiche 
  ADD CONSTRAINT FK_4C13CC789B7F64D9 FOREIGN KEY (codirecteur_id)
    REFERENCES enseignant(id);

--
-- Create foreign key
--
ALTER TABLE fiche 
  ADD CONSTRAINT FK_4C13CC789E6398AF FOREIGN KEY (finaliste_id)
    REFERENCES finaliste(id);

--
-- Create foreign key
--
ALTER TABLE fiche 
  ADD CONSTRAINT FK_4C13CC789FA86DF5 FOREIGN KEY (directeur_propose_id)
    REFERENCES enseignant(id);

--
-- Create foreign key
--
ALTER TABLE fiche 
  ADD CONSTRAINT FK_4C13CC78B00F076 FOREIGN KEY (annee_academique_id)
    REFERENCES annee_academique(id);

--
-- Create foreign key
--
ALTER TABLE fiche 
  ADD CONSTRAINT FK_4C13CC78B0F3904E FOREIGN KEY (directeur_retenu_id)
    REFERENCES enseignant(id);

--
-- Create table `proposition`
--
CREATE TABLE proposition (
  id INT(11) NOT NULL AUTO_INCREMENT,
  fiche_id INT(11) NOT NULL,
  sujet VARCHAR(255) NOT NULL,
  resume LONGTEXT NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_C7CDC353DF522508` on table `proposition`
--
ALTER TABLE proposition 
  ADD INDEX IDX_C7CDC353DF522508(fiche_id);

--
-- Create foreign key
--
ALTER TABLE proposition 
  ADD CONSTRAINT FK_C7CDC353DF522508 FOREIGN KEY (fiche_id)
    REFERENCES fiche(id);

--
-- Create table `paiement_depot`
--
CREATE TABLE paiement_depot (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  fiche_id INT(11) NOT NULL,
  date DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_EF0C6EA3A76ED395` on table `paiement_depot`
--
ALTER TABLE paiement_depot 
  ADD INDEX IDX_EF0C6EA3A76ED395(user_id);

--
-- Create index `UNIQ_EF0C6EA3DF522508` on table `paiement_depot`
--
ALTER TABLE paiement_depot 
  ADD UNIQUE INDEX UNIQ_EF0C6EA3DF522508(fiche_id);

--
-- Create foreign key
--
ALTER TABLE paiement_depot 
  ADD CONSTRAINT FK_EF0C6EA3A76ED395 FOREIGN KEY (user_id)
    REFERENCES user(id);

--
-- Create foreign key
--
ALTER TABLE paiement_depot 
  ADD CONSTRAINT FK_EF0C6EA3DF522508 FOREIGN KEY (fiche_id)
    REFERENCES fiche(id);

--
-- Create table `ligne_cloture`
--
CREATE TABLE ligne_cloture (
  id INT(11) NOT NULL AUTO_INCREMENT,
  fiche_id INT(11) NOT NULL,
  cote DOUBLE NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `UNIQ_4A498B51DF522508` on table `ligne_cloture`
--
ALTER TABLE ligne_cloture 
  ADD UNIQUE INDEX UNIQ_4A498B51DF522508(fiche_id);

--
-- Create foreign key
--
ALTER TABLE ligne_cloture 
  ADD CONSTRAINT FK_4A498B51DF522508 FOREIGN KEY (fiche_id)
    REFERENCES fiche(id);

--
-- Create table `depot`
--
CREATE TABLE depot (
  id INT(11) NOT NULL AUTO_INCREMENT,
  fiche_id INT(11) NOT NULL,
  date DATETIME NOT NULL,
  fichier VARCHAR(255) DEFAULT NULL,
  note_etudiant LONGTEXT NOT NULL,
  note_directeur LONGTEXT DEFAULT NULL,
  is_corrige TINYINT(1) NOT NULL,
  fichier_corrige_directeur VARCHAR(255) DEFAULT NULL,
  date_depot DATE NOT NULL,
  date_correction DATE DEFAULT NULL,
  demandez_rendez_vous TINYINT(1) NOT NULL,
  date_rendez_vous DATETIME DEFAULT NULL,
  titre VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_47948BBCDF522508` on table `depot`
--
ALTER TABLE depot 
  ADD INDEX IDX_47948BBCDF522508(fiche_id);

--
-- Create foreign key
--
ALTER TABLE depot 
  ADD CONSTRAINT FK_47948BBCDF522508 FOREIGN KEY (fiche_id)
    REFERENCES fiche(id);

--
-- Create table `salle`
--
CREATE TABLE salle (
  id INT(11) NOT NULL AUTO_INCREMENT,
  designation VARCHAR(255) NOT NULL,
  description VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `UNIQ_4E977E5C8947610D` on table `salle`
--
ALTER TABLE salle 
  ADD UNIQUE INDEX UNIQ_4E977E5C8947610D(designation);

--
-- Create table `planification`
--
CREATE TABLE planification (
  id INT(11) NOT NULL AUTO_INCREMENT,
  salle_id INT(11) NOT NULL,
  date DATETIME NOT NULL,
  is_validee TINYINT(1) NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  minutes_defense INT(11) NOT NULL,
  minutes_pause INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_FFC02E1BDC304035` on table `planification`
--
ALTER TABLE planification 
  ADD INDEX IDX_FFC02E1BDC304035(salle_id);

--
-- Create foreign key
--
ALTER TABLE planification 
  ADD CONSTRAINT FK_FFC02E1BDC304035 FOREIGN KEY (salle_id)
    REFERENCES salle(id);

--
-- Create table `ligne_planification`
--
CREATE TABLE ligne_planification (
  id INT(11) NOT NULL AUTO_INCREMENT,
  fiche_id INT(11) NOT NULL,
  planification_id INT(11) NOT NULL,
  heure_debut DATETIME NOT NULL,
  heure_fin DATETIME NOT NULL,
  observation VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_F4A32F7AE65142C2` on table `ligne_planification`
--
ALTER TABLE ligne_planification 
  ADD INDEX IDX_F4A32F7AE65142C2(planification_id);

--
-- Create index `UNIQ_F4A32F7ADF522508` on table `ligne_planification`
--
ALTER TABLE ligne_planification 
  ADD UNIQUE INDEX UNIQ_F4A32F7ADF522508(fiche_id);

--
-- Create foreign key
--
ALTER TABLE ligne_planification 
  ADD CONSTRAINT FK_F4A32F7ADF522508 FOREIGN KEY (fiche_id)
    REFERENCES fiche(id);

--
-- Create foreign key
--
ALTER TABLE ligne_planification 
  ADD CONSTRAINT FK_F4A32F7AE65142C2 FOREIGN KEY (planification_id)
    REFERENCES planification(id);

--
-- Create table `ligne_planification_enseignant`
--
CREATE TABLE ligne_planification_enseignant (
  ligne_planification_id INT(11) NOT NULL,
  enseignant_id INT(11) NOT NULL,
  PRIMARY KEY (ligne_planification_id, enseignant_id)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_unicode_ci;

--
-- Create index `IDX_E63F296A62E4E27D` on table `ligne_planification_enseignant`
--
ALTER TABLE ligne_planification_enseignant 
  ADD INDEX IDX_E63F296A62E4E27D(ligne_planification_id);

--
-- Create index `IDX_E63F296AE455FCC0` on table `ligne_planification_enseignant`
--
ALTER TABLE ligne_planification_enseignant 
  ADD INDEX IDX_E63F296AE455FCC0(enseignant_id);

--
-- Create foreign key
--
ALTER TABLE ligne_planification_enseignant 
  ADD CONSTRAINT FK_E63F296A62E4E27D FOREIGN KEY (ligne_planification_id)
    REFERENCES ligne_planification(id) ON DELETE CASCADE;

--
-- Create foreign key
--
ALTER TABLE ligne_planification_enseignant 
  ADD CONSTRAINT FK_E63F296AE455FCC0 FOREIGN KEY (enseignant_id)
    REFERENCES enseignant(id) ON DELETE CASCADE;

-- 
-- Restore previous SQL mode
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;