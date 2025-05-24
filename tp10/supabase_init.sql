-- Create exercice table
CREATE TABLE IF NOT EXISTS exercice (
    id SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(100) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create guerrier table
CREATE TABLE IF NOT EXISTS guerrier (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    degats INTEGER NOT NULL DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add some sample data for exercice
INSERT INTO exercice (titre, auteur) VALUES
('Introduction à PHP', 'Jean Dupont'),
('Programmation Orientée Objet', 'Marie Martin'),
('Base de données et SQL', 'Pierre Durand'),
('API REST avec PHP', 'Sophie Lefebvre');

-- Add some sample guerriers
INSERT INTO guerrier (nom, degats) VALUES
('Aragorn', 10),
('Legolas', 5),
('Gimli', 15),
('Boromir', 25); 