INSERT INTO roles (name) VALUES
('admin'),
('client'),
('livreur');

CREATE EXTENSION IF NOT EXISTS pgcrypto;

INSERT INTO users (name, email, phone, password, role_id, is_active, created_at, updated_at) VALUES
('Admin Gaznika', 'admin@gaznika.com', '+261341234567', crypt('password123', gen_salt('bf')), 1, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Jean Dupont', 'jean.dupont@example.com', '+261341234568', crypt('client123', gen_salt('bf')), 2, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Marie Rakoto', 'marie.rakoto@example.com', '+261341234569', crypt('client123', gen_salt('bf')), 2, FALSE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Paul Rabe', 'paul.rabe@example.com', '+261341234570', crypt('livreur123', gen_salt('bf')), 3, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
















-- =============================================
-- TYPES ÉNUMÉRÉS
-- =============================================

-- Types de produits
CREATE TYPE product_type AS ENUM ('digesteur', 'bouteille_gaz', 'service_installation', 'maintenance');

-- Formats de bouteilles
CREATE TYPE bottle_format AS ENUM ('6kg', '12_5kg', 'special');

-- Modèles de digesteurs
CREATE TYPE digesteur_model AS ENUM ('residentiel', 'commercial', 'industriel');

-- Statuts de commande
CREATE TYPE order_status AS ENUM ('en_preparation', 'en_cours_livraison', 'livre', 'probleme', 'annule');

-- Modes de paiement
CREATE TYPE payment_method AS ENUM ('orange_money');

-- Enum PostgreSQL pour sécuriser les statuts de paiement
CREATE TYPE payment_status_enum AS ENUM ('en_attente', 'valide', 'echec', 'rembourse');

-- Types d'adresse
CREATE TYPE address_type AS ENUM ('domicile', 'bureau', 'autre');

-- Statuts de ticket support
CREATE TYPE ticket_status AS ENUM ('ouvert', 'en_cours', 'resolu', 'ferme');


-- Types de notification
CREATE TYPE notification_type AS ENUM ('commande', 'livraison', 'maintenance', 'promotion', 'systeme');


-- Types de transaction
CREATE TYPE transaction_type AS ENUM ('achat', 'remboursement', 'bonus_fidelite', 'parrainage');



-- =============================================
-- INSERTIONS INITIALES : Produits
-- =============================================

INSERT INTO products (name, description, type, bottle_format, price, stock_quantity) VALUES
('Bouteille de gaz 6kg', 'Bouteille de gaz écologique 6kg', 'bouteille_gaz', '6kg', 15000.00, 100),
('Bouteille de gaz 12,5kg', 'Bouteille de gaz écologique 12,5kg', 'bouteille_gaz', '12_5kg', 25000.00, 150),
('Digesteur résidentiel', 'Digesteur pour usage domestique', 'digesteur', NULL, 500000.00, 10),
('Digesteur commercial', 'Digesteur pour usage commercial', 'digesteur', NULL, 1500000.00, 5),
('Installation standard', 'Service d''installation standard', 'service_installation', NULL, 50000.00, 999),
('Maintenance préventive', 'Service de maintenance préventive', 'maintenance', NULL, 25000.00, 999);


-- =============================================
-- ENUM TYPES
-- =============================================

CREATE TYPE client_type AS ENUM ('menage', 'entreprise');
CREATE TYPE product_type AS ENUM ('digesteur', 'bouteille_gaz', 'service_installation', 'maintenance');
CREATE TYPE bottle_format AS ENUM ('6kg', '12_5kg', 'special');
CREATE TYPE digesteur_model AS ENUM ('residentiel', 'commercial', 'industriel');
CREATE TYPE order_status AS ENUM ('en_preparation', 'en_cours_livraison', 'livre', 'probleme', 'annule');
CREATE TYPE payment_method AS ENUM ('orange_money');
CREATE TYPE payment_status AS ENUM ('en_attente', 'valide', 'echec', 'rembourse');
CREATE TYPE address_type AS ENUM ('domicile', 'bureau', 'autre');
CREATE TYPE ticket_status AS ENUM ('ouvert', 'en_cours', 'resolu', 'ferme');
CREATE TYPE ticket_priority AS ENUM ('basse', 'normale', 'haute', 'critique');
CREATE TYPE notification_type AS ENUM ('commande', 'livraison', 'maintenance', 'promotion', 'systeme');
CREATE TYPE loyalty_level AS ENUM ('bronze', 'argent', 'or', 'platine');
CREATE TYPE transaction_type AS ENUM ('achat', 'remboursement', 'bonus_fidelite', 'parrainage');

-- =============================================
-- TABLES
-- =============================================
