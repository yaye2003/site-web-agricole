-- Crée la base si elle n'existe pas
CREATE DATABASE IF NOT EXISTS projet_agri;
USE projet_agri;

-- Suppression des anciennes tables
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS utilisateur;

-- Table utilisateur
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('acheteur','fournisseur') NOT NULL,
    identification VARCHAR(255) DEFAULT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table catégorie
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- Table produit
CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    categorie_id INT NOT NULL,
    fournisseur_id INT NOT NULL,
    image VARCHAR(255),
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE CASCADE,
    FOREIGN KEY (fournisseur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Table commande
CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    acheteur_id INT NOT NULL,
    produit_id INT NOT NULL,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(30) DEFAULT 'en attente',
    FOREIGN KEY (acheteur_id) REFERENCES utilisateur(id),
    FOREIGN KEY (produit_id) REFERENCES produit(id)
);

-- Insertion des catégories
INSERT INTO categorie (nom) VALUES ('Semences'), ('Légumes'), ('Fruits'), ('Végétales'), ('Produits');

-- Stocker les IDs des catégories dans des variables
SET @cat_semences = (SELECT id FROM categorie WHERE nom='Semences');
SET @cat_legumes = (SELECT id FROM categorie WHERE nom='Légumes');
SET @cat_fruits = (SELECT id FROM categorie WHERE nom='Fruits');
SET @cat_vegetales = (SELECT id FROM categorie WHERE nom='Végétales');
SET @cat_produits = (SELECT id FROM categorie WHERE nom='Produits');

-- Insertion des fournisseurs
INSERT INTO utilisateur (nom, prenom, email, role, identification) VALUES
('Fall', 'Papa', 'papa_fall@exemple.com', 'fournisseur', 'F001'),
('Diop', 'Fatou', 'fatou_diop@exemple.com', 'fournisseur', 'F002'),
('Sow', 'Mawdo', 'mawdo_sow@exemple.com', 'fournisseur', 'F003'),
('Mboup', 'Nafi', 'nafi_mboup@exemple.com', 'fournisseur', 'F004'),
('Wade', 'Bouba', 'bouba_wade@exemple.com', 'fournisseur', 'F005'),
('Faye', 'Mada', 'mada_faye@exemple.com', 'fournisseur', 'F006'),
('Gueye', 'Alioune', 'alioune_gueye@exemple.com', 'fournisseur', 'F007');

-- Stocker les IDs des fournisseurs
SET @f1 = (SELECT id FROM utilisateur WHERE email='papa_fall@exemple.com');
SET @f2 = (SELECT id FROM utilisateur WHERE email='fatou_diop@exemple.com');
SET @f3 = (SELECT id FROM utilisateur WHERE email='mawdo_sow@exemple.com');
SET @f4 = (SELECT id FROM utilisateur WHERE email='nafi_mboup@exemple.com');
SET @f5 = (SELECT id FROM utilisateur WHERE email='bouba_wade@exemple.com');
SET @f6 = (SELECT id FROM utilisateur WHERE email='mada_faye@exemple.com');
SET @f7 = (SELECT id FROM utilisateur WHERE email='alioune_gueye@exemple.com');

-- Insertion des produits Semences
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Semence de Maïs', 'Semence de maïs hybride', 1000, @cat_semences, @f1, '../images/mais.jfif'),
('Semence de Riz', 'Semence de riz local', 1200, @cat_semences, @f2, '../images/riz1.avif'),
('Semence de Tomate', 'Semence de tomates résistantes', 800, @cat_semences, @f3, '../images/tomate.jfif');

-- Insertion de quelques fruits
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Pomme', 'Pommes rouges', 300, @cat_fruits, @f7, '../images/pomme1.jpg'),
('Banane', 'Bananes fraîches', 250, @cat_fruits, @f5, '../images/banane.jpg'),
('Mangue', 'Mangues bio', 400, @cat_fruits, @f6, '../images/mangue1.jpg');

-- Insertion de légumes
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Tomate', 'Tomates fraîches', 200, @cat_legumes, @f1, '../images/tomate1.jpg'),
('Carotte', 'Carottes bio', 150, @cat_legumes, @f4, '../images/carotte.jfif'),
('Concombre', 'Concombres bio', 120, @cat_legumes, @f7, '../images/concombre.jpg'),
('Poivron', 'Poivrons rouges', 180, @cat_legumes, @f1, '../images/poivron.jpg'),
('Aubergine', 'Aubergines fraîches', 200, @cat_legumes, @f2, '../images/aubergine.jfif');

-- Insertion de végétales
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Épinard', 'Épinards bio', 90, @cat_vegetales, @f3, '../images/epinard.jfif'),
('Laitue', 'Laitues vertes', 80, @cat_vegetales, @f6, '../images/laitue.jpg'),
('Céleri', 'Céleri frais', 110, @cat_vegetales, @f2, '../images/celeri.jpg'),
('Chou', 'Chou vert', 100, @cat_vegetales, @f1, '../images/chou.webp');

-- Insertion de produits divers
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Riz', 'Riz local', 500, @cat_produits, @f2, '../images/riz2.jpg'),
('Maïs', 'Maïs local', 300, @cat_produits, @f7, '../images/mais_1.jfif'),
('Arachide', 'Arachides grillées', 200, @cat_produits, @f3, '../images/arachide1.png'),
('Haricot', 'Haricots secs', 250, @cat_produits, @f5, '../images/haricot.jpg');

-- Insertion de semences supplémentaires
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Semence de Carotte', 'Semence bio de carottes', 900, @cat_semences, @f1, '../images/carotte1.jpg'),
('Semence de Piment', 'Semence de piments forts', 1100, @cat_semences, @f2, '../images/piment.jfif'),
('Semence de Maïs Doux', 'Semence de maïs doux', 950, @cat_semences, @f3, '../images/mais2.jpg');

-- Insertion de fruits supplémentaires
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Orange', 'Oranges juteuses', 350, @cat_fruits, @f6, '../images/orange.webp'),
('Mangoustan', 'Mangoustan bio', 600, @cat_fruits, @f5, '../images/mangoustan1.jfif'),
('Citron', 'Citrons jaunes', 200, @cat_fruits, @f7, '../images/citron.jpg');

-- =========================================
-- Semences (15 produits uniques)
-- =========================================
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Semence de Maïs', 'Semence de maïs hybride', 1000, @cat_semences, @f1, '../images/mais2.jpg'),
('Semence de Riz', 'Semence de riz local', 1200, @cat_semences, @f2, '../images/riz3.jfif'),
('Semence de Tomate', 'Semence de tomates résistantes', 800, @cat_semences, @f3, '../images/tomate2.jfif'),
('Semence de Carotte', 'Semence bio de carottes', 900, @cat_semences, @f4, '../images/carotte2.jfif'),
('Semence de Piment', 'Semence de piments forts', 1100, @cat_semences, @f5, '../images/piment4.jpg'),
('Semence de Concombre', 'Semence de concombres bio', 950, @cat_semences, @f6, '../images/concombre.jpg'),
('Semence de Courgette', 'Semence bio de courgettes', 870, @cat_semences, @f1, '../images/courgette2.webp'),
('Semence de Poivron', 'Semence de poivrons rouges', 880, @cat_semences, @f7, '../images/poivron.jpg'),
('Semence d’Aubergine', 'Semence d’aubergines fraîches', 920, @cat_semences, @f3, '../images/aubergine.jfif'),
('Semence d’Épinard', 'Semence d’épinards bio', 780, @cat_semences, @f1, '../images/epinard1.jfif'),
('Semence de Laitue', 'Semence de laitues vertes', 790, @cat_semences, @f2, '../images/laitue4.jpg'),
('Semence de Céleri', 'Semence de céleri frais', 850, @cat_semences, @f4, '../images/celeri.jpg'),
('Semence de Chou', 'Semence de chou vert', 820, @cat_semences, @f1, '../images/chou1.webp'),
('Semence de Haricot', 'Semence d’haricots secs', 900, @cat_semences, @f2, '../images/haricot3.jfif'),
('Semence d’Arachide', 'Semence d’arachides locales', 970, @cat_semences, @f3, '../images/arachide3.avif');

-- =========================================
-- Légumes (15 produits uniques)
-- =========================================
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Tomate fraîche', 'Tomates fraîches du jardin', 200, @cat_legumes, @f1, '../images/tomate3.webp'),
('Carotte bio', 'Carottes bio du potager', 150, @cat_legumes, @f2, '../images/carotte3.webp'),
('Concombre vert', 'Concombres bio', 120, @cat_legumes, @f3, '../images/concombre1.avif'),
('Poivron rouge', 'Poivrons rouges frais', 180, @cat_legumes, @f7, '../images/poivron1.jfif'),
('Aubergine violette', 'Aubergines fraîches', 200, @cat_legumes, @f6, '../images/aubergine1.jfif'),
('Courgette jaune', 'Courgettes bio', 170, @cat_legumes, @f5, '../images/courgette1.jfif'),
('Navet blanc', 'Navets locaux', 130, @cat_legumes, @f7, '../images/navet2.jpg'),
('Radis rose', 'Radis frais du marché', 110, @cat_legumes, @f2, '../images/radis.jfif'),
('Betterave rouge', 'Betteraves bio', 140, @cat_legumes, @f3, '../images/betterave.jpg'),
('Chou-fleur blanc', 'Chou-fleurs frais', 190, @cat_legumes, @f1, '../images/choufleur.avif'),
('Brocoli vert', 'Brocoli bio', 220, @cat_legumes, @f4, '../images/brocoli.jfif'),
('Épinard frais', 'Épinards du jardin', 100, @cat_legumes, @f4, '../images/epinard3.png'),
('Laitue verte', 'Laitues fraîches', 80, @cat_legumes, @f4, '../images/laitue5.jfif'),
('Céleri vert', 'Céleri frais du potager', 110, @cat_legumes, @f1, '../images/celeri3.jfif'),
('Chou vert', 'Chou vert bio', 100, @cat_legumes, @f3, '../images/chou4.jfif');

-- =========================================
-- Fruits (15 produits uniques)
-- =========================================
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Pomme rouge', 'Pommes rouges fraîches', 300, @cat_fruits, @f7, '../images/pomme.jpg'),
('Banane jaune', 'Bananes mûres', 250, @cat_fruits, @f6, '../images/banane2.webp'),
('Mangue sucrée', 'Mangues bio', 400, @cat_fruits, @f5, '../images/mangue.jpg'),
('Orange juteuse', 'Oranges locales', 350, @cat_fruits, @f4, '../images/orange1jpg.jpg'),
('Citron vert', 'Citrons frais', 200, @cat_fruits, @f3, '../images/citron4.jfif'),
('Ananas doré', 'Ananas bio', 500, @cat_fruits, @f3, '../images/ananas1.jpg'),
('Papaye mûre', 'Papayes bio', 450, @cat_fruits, @f1, '../images/papaye1.jfif'),
('Goyave rose', 'Goyaves fraîches', 300, @cat_fruits, @f2, '../images/goyave1.jpg'),
('Pastèque rouge', 'Pastèques locales', 400, @cat_fruits, @f3, '../images/pasteque2.png'),
('Melon jaune', 'Melons sucrés', 350, @cat_fruits, @f1, '../images/melon.jpg'),
('Mangoustan bio', 'Mangoustans bio', 600, @cat_fruits, @f2, '../images/mangoustan1.jpg'),
('Figue violette', 'Figues locales', 550, @cat_fruits, @f3, '../images/figu.jfif'),
('Prune rouge', 'Prunes fraîches', 300, @cat_fruits, @f4, '../images/prune.webp'),
('Cerise noire', 'Cerises fraîches', 700, @cat_fruits, @f2, '../images/cerise1.jpg'),
('Kiwi vert', 'Kiwis bio', 500, @cat_fruits, @f7, '../images/kiwi.jpg');

-- =========================================
-- Végétales (15 produits uniques)
-- =========================================
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Épinard bio', 'Épinards bio', 90, @cat_vegetales, @f1, '../images/epinard4.avif'),
('Laitue fraîche', 'Laitues vertes', 80, @cat_vegetales, @f2, '../images/laitue2.jfif'),
('Céleri bio', 'Céleri frais', 110, @cat_vegetales, @f3, '../images/celeri1.jfif'),
('Chou vert bio', 'Chou vert', 100, @cat_vegetales, @f1, '../images/chou4.jfif'),
('Blette rouge', 'Blettes locales', 95, @cat_vegetales, @f2, '../images/blette.webp'),
('Roquette bio', 'Roquette fraîche', 85, @cat_vegetales, @f3, '../images/roquette.jfif'),
('Persil frais', 'Persil vert', 70, @cat_vegetales, @f1, '../images/persil.webp'),
('Coriandre bio', 'Coriandre fraîche', 75, @cat_vegetales, @f2, '../images/coriandre.avif'),
('Basilic frais', 'Basilic vert', 80, @cat_vegetales, @f3, '../images/basilic.jpg'),
('Menthe bio', 'Menthe fraîche', 65, @cat_vegetales, @f1, '../images/menthe.jpg'),
('Oseille bio', 'Oseille locale', 90, @cat_vegetales, @f2, '../images/oseille.jfif'),
('Ciboulette fraîche', 'Ciboulette', 70, @cat_vegetales, @f3, '../images/ciboulette.jpg'),
('Fenouil bio', 'Fenouil frais', 85, @cat_vegetales, @f1, '../images/fenouil.png'),
('Chou frisé bio', 'Chou frisé', 100, @cat_vegetales, @f2, '../images/choué.jpg'),
('Radicchio rouge', 'Radicchio', 120, @cat_vegetales, @f3, '../images/radicchio.jpg');

-- =========================================
-- Produits divers (15 produits uniques)
-- =========================================
INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image) VALUES
('Riz local', 'Riz local', 500, @cat_produits, @f1, '../images/riz3.jfif'),
('Maïs local', 'Maïs bio', 300, @cat_produits, @f2, '../images/maisjpg.jpg'),
('Arachide grillée', 'Arachides grillées', 200, @cat_produits, @f3, '../images/arachide3.avif'),
('Haricot sec', 'Haricots secs', 250, @cat_produits, @f1, '../images/haricot1.jpg'),
('Sorgho bio', 'Sorgho', 400, @cat_produits, @f2, '../images/sorgo.jpeg'),
('Mil local', 'Mil du champ', 350, @cat_produits, @f3, '../images/mil.webp'),
('Blé bio', 'Blé frais', 450, @cat_produits, @f1, '../images/ble.webp'),
('Avoine locale', 'Avoine', 420, @cat_produits, @f2, '../images/avoine.jpg'),
('Orge bio', 'Orge fraîche', 430, @cat_produits, @f3, '../images/orge.jfif'),
('Pois chiche bio', 'Pois chiches', 380, @cat_produits, @f1, '../images/poischiche.jpg'),
('Lentille locale', 'Lentilles', 390, @cat_produits, @f2, '../images/lentille.webp'),
('Soja bio', 'Soja frais', 410, @cat_produits, @f3, '../images/soja.jfif'),
('Amande fraîche', 'Amandes', 500, @cat_produits, @f1, '../images/amande.jpeg'),
('Noix bio', 'Noix', 550, @cat_produits, @f2, '../images/noix.jfif'),
('Cacahuète grillée', 'Cacahuètes', 480, @cat_produits, @f3, '../images/cacahuete.jfif');

