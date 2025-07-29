

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password TEXT NOT NULL,
    role_id INTEGER REFERENCES roles(id),
    client_type client_type,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    street_address TEXT NOT NULL,
    city VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100),
    region_id INTEGER,
    type address_type,
    is_default BOOLEAN DEFAULT FALSE
);

CREATE TABLE regions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) UNIQUE
);
-- Table des produits
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    type product_type,
    bottle_format bottle_format,
    price NUMERIC(10,2),
    stock_quantity INTEGER,
    weight NUMERIC(6,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Table des commandes

CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    address_id INTEGER REFERENCES addresses(id),
    total_price NUMERIC(10,2),
    delivery_type VARCHAR(50),
    status order_status,
    scheduled_at TIMESTAMP,
    payment_method payment_method,
    payment_status payment_status,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Détails de commande

CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id),
    quantity INTEGER,
    unit_price NUMERIC(10,2)
);
-- Historique des statuts pour chaque commande

CREATE TABLE order_status_history (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    status order_status NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Paiements

CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    method payment_method,
    amount NUMERIC(10,2),
    status payment_status,
    transaction_id VARCHAR(100),
    paid_at TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Livraisons

CREATE TABLE deliveries (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    driver_id INTEGER REFERENCES users(id),
    status VARCHAR(50),
    geo_location JSONB,
    estimated_time INTERVAL,
    delivered_at TIMESTAMP,
    notes TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Points de collecte

CREATE TABLE collection_points (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    address TEXT,
    latitude NUMERIC(9,6),
    longitude NUMERIC(9,6),
    region VARCHAR(100)
);
-- Collectes

CREATE TABLE collections (
    id SERIAL PRIMARY KEY,
    point_id INTEGER REFERENCES collection_points(id),
    collector_id INTEGER REFERENCES users(id),
    scheduled_at TIMESTAMP,
    collected_at TIMESTAMP,
    tonnage NUMERIC(10,2),
    notes TEXT
);
-- Digesteurs

CREATE TABLE digesters (
    id SERIAL PRIMARY KEY,
    serial_number VARCHAR(100) UNIQUE,
    location_address TEXT,
    client_id INTEGER REFERENCES users(id),
    installed_at TIMESTAMP,
    is_connected BOOLEAN DEFAULT FALSE,
    model digesteur_model,
    manual_url TEXT
);
-- Données des capteurs

CREATE TABLE sensor_data (
    id SERIAL PRIMARY KEY,
    digester_id INTEGER REFERENCES digesters(id) ON DELETE CASCADE,
    pressure NUMERIC(6,2),
    temperature NUMERIC(6,2),
    gas_level NUMERIC(6,2),
    battery_level NUMERIC(6,2),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Tickets de support

CREATE TABLE support_tickets (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    subject VARCHAR(255),
    message TEXT,
    status ticket_status,
    assigned_to INTEGER REFERENCES users(id),
    priority ticket_priority,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Messages liés aux tickets

CREATE TABLE ticket_messages (
    id SERIAL PRIMARY KEY,
    ticket_id INTEGER REFERENCES support_tickets(id) ON DELETE CASCADE,
    sender_id INTEGER REFERENCES users(id),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    type notification_type,
    message TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE statistics (
    id SERIAL PRIMARY KEY,
    metric VARCHAR(100),
    region VARCHAR(100),
    value NUMERIC(12,4),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source VARCHAR(50)
);

CREATE TABLE chat_sessions (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ended_at TIMESTAMP,
    escalated_to_human BOOLEAN DEFAULT FALSE
);

CREATE TABLE chat_messages (
    id SERIAL PRIMARY KEY,
    session_id INTEGER REFERENCES chat_sessions(id) ON DELETE CASCADE,
    message_text TEXT NOT NULL,
    is_from_bot BOOLEAN,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
