-- =============================================
-- BASE DE DONNÉES GAZNIKA - STRUCTURE POSTGRESQL
-- =============================================

-- Extensions nécessaires
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "postgis";

-- =============================================
-- TYPES ÉNUMÉRÉS
-- =============================================

-- Types de clients
CREATE TYPE client_type AS ENUM ('menage', 'entreprise');

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

-- Statuts de paiement
CREATE TYPE payment_status AS ENUM ('en_attente', 'valide', 'echec', 'rembourse');

-- Types d'adresse
CREATE TYPE address_type AS ENUM ('domicile', 'bureau', 'autre');

-- Statuts de ticket support
CREATE TYPE ticket_status AS ENUM ('ouvert', 'en_cours', 'resolu', 'ferme');

-- Priorités de ticket
CREATE TYPE ticket_priority AS ENUM ('basse', 'normale', 'haute', 'critique');

-- Types de notification
CREATE TYPE notification_type AS ENUM ('commande', 'livraison', 'maintenance', 'promotion', 'systeme');

-- Niveaux de fidélité
CREATE TYPE loyalty_level AS ENUM ('bronze', 'argent', 'or', 'platine');

-- Types de transaction
CREATE TYPE transaction_type AS ENUM ('achat', 'remboursement', 'bonus_fidelite', 'parrainage');

-- =============================================
-- TABLES PRINCIPALES
-- =============================================

-- Table des utilisateurs
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    client_type client_type NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    company_name VARCHAR(255),
    company_registration VARCHAR(100),
    email_verified_at TIMESTAMP,
    phone_verified_at TIMESTAMP,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    language VARCHAR(10) DEFAULT 'fr',
    --loyalty_level loyalty_level DEFAULT 'bronze',
    loyalty_points INTEGER DEFAULT 0,
    account_balance DECIMAL(10,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);
CREATE TYPE transaction_type AS ENUM ('achat', 'remboursement', 'bonus_fidelite', 'parrainage');

-- Table des adresses
CREATE TABLE addresses (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    address_type address_type NOT NULL,
    label VARCHAR(100),
    street_address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20),
    region VARCHAR(100),
    country VARCHAR(100) DEFAULT 'Madagascar',
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    delivery_instructions TEXT,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Table des produits
CREATE TABLE products (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name VARCHAR(255) NOT NULL,
    description TEXT,
    product_type product_type NOT NULL,
    bottle_format bottle_format,
    --digesteur_model digesteur_model,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INTEGER DEFAULT 0,
    min_stock_level INTEGER DEFAULT 0,
    weight DECIMAL(8,2),
    dimensions VARCHAR(100),
    technical_specs JSONB,
    images JSONB,
    videos JSONB,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Table des commandes
CREATE TABLE orders (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    status order_status DEFAULT 'en_preparation',
    delivery_address_id UUID REFERENCES addresses(id),
    delivery_type VARCHAR(50) DEFAULT 'livraison',
    delivery_date DATE,
    delivery_time_slot VARCHAR(50),
    special_instructions TEXT,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) DEFAULT 0.00,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method payment_method,
    payment_status payment_status DEFAULT 'en_attente',
    delivery_latitude DECIMAL(10,8),
    delivery_longitude DECIMAL(11,8),
    estimated_delivery_time TIMESTAMP,
    actual_delivery_time TIMESTAMP,
    delivery_rating INTEGER CHECK (delivery_rating >= 1 AND delivery_rating <= 5),
    delivery_comment TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Table des articles de commande
CREATE TABLE order_items (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    order_id UUID REFERENCES orders(id) ON DELETE CASCADE,
    product_id UUID REFERENCES products(id),
    quantity INTEGER NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des paiements
CREATE TABLE payments (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    order_id UUID REFERENCES orders(id) ON DELETE CASCADE,
    payment_method payment_method NOT NULL,
    payment_status payment_status DEFAULT 'en_attente',
    amount DECIMAL(10,2) NOT NULL,
    transaction_id VARCHAR(255),
    provider_reference VARCHAR(255),
    payment_date TIMESTAMP,
    refund_amount DECIMAL(10,2) DEFAULT 0.00,
    refund_date TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Table des abonnements
CREATE TABLE subscriptions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    product_id UUID REFERENCES products(id),
    delivery_address_id UUID REFERENCES addresses(id),
    frequency_days INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    next_delivery_date DATE,
    last_delivery_date DATE,
    is_active BOOLEAN DEFAULT TRUE,
    is_paused BOOLEAN DEFAULT FALSE,
    pause_until DATE,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Table des tickets de support
CREATE TABLE support_tickets (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    order_id UUID REFERENCES orders(id) ON DELETE SET NULL,
    ticket_number VARCHAR(50) UNIQUE NOT NULL,
    subject VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    --priority ticket_priority DEFAULT 'normale',
    --status ticket_status DEFAULT 'ouvert',
    description TEXT NOT NULL,
    assigned_to VARCHAR(255),
    resolution_notes TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    resolved_at TIMESTAMP
);

-- Table des messages de support
CREATE TABLE support_messages (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    --ticket_id UUID REFERENCES support_tickets(id) ON DELETE CASCADE,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    message TEXT NOT NULL,
    is_from_customer BOOLEAN DEFAULT TRUE,
    attachments JSONB,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des sessions de chat
CREATE TABLE chat_sessions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    language VARCHAR(10) DEFAULT 'fr',
    is_active BOOLEAN DEFAULT TRUE,
    escalated_to_human BOOLEAN DEFAULT FALSE,
    agent_id VARCHAR(255),
    context_data JSONB,
    started_at TIMESTAMP DEFAULT NOW(),
    ended_at TIMESTAMP
);

-- Table des messages de chat
CREATE TABLE chat_messages (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    session_id UUID REFERENCES chat_sessions(id) ON DELETE CASCADE,
    message_text TEXT NOT NULL,
    is_from_bot BOOLEAN DEFAULT FALSE,
    bot_confidence DECIMAL(3,2),
    intent_detected VARCHAR(100),
    response_time_ms INTEGER,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des factures
CREATE TABLE invoices (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    order_id UUID REFERENCES orders(id) ON DELETE CASCADE,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE,
    subtotal DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status payment_status DEFAULT 'en_attente',
    pdf_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Table des transactions
CREATE TABLE transactions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    order_id UUID REFERENCES orders(id) ON DELETE SET NULL,
    transaction_type transaction_type NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    balance_before DECIMAL(10,2) NOT NULL,
    balance_after DECIMAL(10,2) NOT NULL,
    description TEXT,
    reference VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des notifications
CREATE TABLE notifications (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    type notification_type NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSONB,
    is_read BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP DEFAULT NOW(),
    read_at TIMESTAMP
);

-- Table des préférences utilisateur
CREATE TABLE user_preferences (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    email_notifications BOOLEAN DEFAULT TRUE,
    sms_notifications BOOLEAN DEFAULT TRUE,
    push_notifications BOOLEAN DEFAULT TRUE,
    marketing_emails BOOLEAN DEFAULT FALSE,
    language VARCHAR(10) DEFAULT 'fr',
    timezone VARCHAR(50) DEFAULT 'Indian/Antananarivo',
    auto_delivery_enabled BOOLEAN DEFAULT FALSE,
    auto_delivery_threshold INTEGER DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Table des codes QR
CREATE TABLE qr_codes (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    product_id UUID REFERENCES products(id) ON DELETE CASCADE,
    qr_code VARCHAR(255) UNIQUE NOT NULL,
    qr_data JSONB NOT NULL,
    scan_count INTEGER DEFAULT 0,
    last_scanned_at TIMESTAMP,
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des points de fidélité
CREATE TABLE loyalty_points (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    order_id UUID REFERENCES orders(id) ON DELETE SET NULL,
    points_earned INTEGER DEFAULT 0,
    points_spent INTEGER DEFAULT 0,
    points_expired INTEGER DEFAULT 0,
    description TEXT,
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des références parrainage
CREATE TABLE referrals (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    referrer_id UUID REFERENCES users(id) ON DELETE CASCADE,
    referee_id UUID REFERENCES users(id) ON DELETE CASCADE,
    referral_code VARCHAR(50) UNIQUE NOT NULL,
    bonus_points INTEGER DEFAULT 0,
    status VARCHAR(50) DEFAULT 'pending',
    completed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des statistiques environnementales
CREATE TABLE environmental_stats (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    co2_saved_kg DECIMAL(10,2) DEFAULT 0.00,
    waste_processed_kg DECIMAL(10,2) DEFAULT 0.00,
    renewable_energy_kwh DECIMAL(10,2) DEFAULT 0.00,
    circular_economy_contribution DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT NOW()
);

-- =============================================
-- INDEX POUR OPTIMISATION
-- =============================================

-- Index sur les colonnes fréquemment utilisées
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_phone ON users(phone);
CREATE INDEX idx_users_loyalty_level ON users(loyalty_level);
CREATE INDEX idx_addresses_user_id ON addresses(user_id);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_order_number ON orders(order_number);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_payments_order_id ON payments(order_id);
CREATE INDEX idx_support_tickets_user_id ON support_tickets(user_id);
CREATE INDEX idx_support_tickets_status ON support_tickets(status);
CREATE INDEX idx_chat_sessions_user_id ON chat_sessions(user_id);
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);
CREATE INDEX idx_transactions_user_id ON transactions(user_id);
CREATE INDEX idx_loyalty_points_user_id ON loyalty_points(user_id);

-- Index composites
CREATE INDEX idx_orders_user_status ON orders(user_id, status);
CREATE INDEX idx_orders_date_status ON orders(created_at, status);
CREATE INDEX idx_products_type_active ON products(product_type, is_active);

-- =============================================
-- TRIGGERS POUR MISE À JOUR AUTOMATIQUE
-- =============================================

-- Fonction pour mise à jour du timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Triggers pour updated_at
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_addresses_updated_at BEFORE UPDATE ON addresses FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_products_updated_at BEFORE UPDATE ON products FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_orders_updated_at BEFORE UPDATE ON orders FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_payments_updated_at BEFORE UPDATE ON payments FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_subscriptions_updated_at BEFORE UPDATE ON subscriptions FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_support_tickets_updated_at BEFORE UPDATE ON support_tickets FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_invoices_updated_at BEFORE UPDATE ON invoices FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_user_preferences_updated_at BEFORE UPDATE ON user_preferences FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- =============================================
-- FONCTIONS UTILITAIRES
-- =============================================

-- Fonction pour générer un numéro de commande
CREATE OR REPLACE FUNCTION generate_order_number()
RETURNS VARCHAR(50) AS $$
BEGIN
    RETURN 'GZN-' || TO_CHAR(NOW(), 'YYYYMMDD') || '-' || LPAD(NEXTVAL('order_sequence')::TEXT, 6, '0');
END;
$$ LANGUAGE plpgsql;

-- Séquence pour les numéros de commande
CREATE SEQUENCE order_sequence START 1;

-- Fonction pour calculer les points de fidélité
CREATE OR REPLACE FUNCTION calculate_loyalty_points(amount DECIMAL)
RETURNS INTEGER AS $$
BEGIN
    RETURN FLOOR(amount)::INTEGER; -- 1 point par euro dépensé
END;
$$ LANGUAGE plpgsql;

-- Fonction pour mettre à jour le niveau de fidélité
CREATE OR REPLACE FUNCTION update_loyalty_level(user_uuid UUID)
RETURNS VOID AS $$
DECLARE
    total_points INTEGER;
    new_level loyalty_level;
BEGIN
    SELECT loyalty_points INTO total_points FROM users WHERE id = user_uuid;
    
    IF total_points >= 10000 THEN
        new_level := 'platine';
    ELSIF total_points >= 5000 THEN
        new_level := 'or';
    ELSIF total_points >= 1000 THEN
        new_level := 'argent';
    ELSE
        new_level := 'bronze';
    END IF;
    
    UPDATE users SET loyalty_level = new_level WHERE id = user_uuid;
END;
$$ LANGUAGE plpgsql;

-- =============================================
-- DONNÉES INITIALES
-- =============================================

-- Insertion des produits de base
INSERT INTO products (name, description, product_type, bottle_format, price, stock_quantity) VALUES
('Bouteille de gaz 6kg', 'Bouteille de gaz écologique 6kg', 'bouteille_gaz', '6kg', 15000.00, 100),
('Bouteille de gaz 12,5kg', 'Bouteille de gaz écologique 12,5kg', 'bouteille_gaz', '12_5kg', 25000.00, 150),
('Digesteur résidentiel', 'Digesteur pour usage domestique', 'digesteur', null, 500000.00, 10),
('Digesteur commercial', 'Digesteur pour usage commercial', 'digesteur', null, 1500000.00, 5),
('Installation standard', 'Service d''installation standard', 'service_installation', null, 50000.00, 999),
('Maintenance préventive', 'Service de maintenance préventive', 'maintenance', null, 25000.00, 999);

-- Création d'un utilisateur admin par défaut
INSERT INTO users (email, password_hash, client_type, first_name, last_name, phone) VALUES
('admin@gaznika.com', crypt('admin123', gen_salt('bf')), 'entreprise', 'Admin', 'GazNika', '+261340000000');

-- =============================================
-- VUES UTILES
-- =============================================

-- Vue pour les commandes avec détails
CREATE VIEW v_orders_details AS
SELECT 
    o.*,
    u.first_name || ' ' || u.last_name as customer_name,
    u.email as customer_email,
    u.phone as customer_phone,
    a.street_address || ', ' || a.city as delivery_address,
    COUNT(oi.id) as items_count,
    SUM(oi.quantity) as total_quantity
FROM orders o
JOIN users u ON o.user_id = u.id
LEFT JOIN addresses a ON o.delivery_address_id = a.id
LEFT JOIN order_items oi ON o.id = oi.order_id
GROUP BY o.id, u.id, a.id;

-- Vue pour les statistiques de fidélité
CREATE VIEW v_loyalty_stats AS
SELECT 
    u.id,
    u.first_name || ' ' || u.last_name as customer_name,
    u.loyalty_level,
    u.loyalty_points,
    COUNT(o.id) as total_orders,
    SUM(o.total_amount) as total_spent,
    MAX(o.created_at) as last_order_date
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
GROUP BY u.id;
