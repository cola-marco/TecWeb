DROP TABLE IF EXISTS Clienti;
DROP TABLE IF EXISTS Autori;
DROP TABLE IF EXISTS Generi;
DROP TABLE IF EXISTS Libri;
DROP TABLE IF EXISTS Prestiti;
DROP TABLE IF EXISTS Wishlist;

-- Tabella dei Clienti
CREATE TABLE Clienti (
    ID_Cliente INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Telefono VARCHAR(15) UNIQUE  NOT NULL,
    Indirizzo VARCHAR(100) NOT NULL,
    Username VARCHAR(100) UNIQUE NOT NULL,
    Pass VARCHAR(100) NOT NULL,
    Ruolo ENUM('Cliente', 'Admin') DEFAULT 'Cliente'
);

-- Tabella per gli Autori
CREATE TABLE Autori (
    ID_Autore INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL
);

-- Tabella per i Generi
CREATE TABLE Generi (
    Nome VARCHAR(100) PRIMARY KEY
);

-- Tabella lista Libri
CREATE TABLE Libri (
    ID_Libro INT AUTO_INCREMENT PRIMARY KEY,
    Titolo VARCHAR(100) NOT NULL,
    Autore INT NOT NULL,
    Casa_Editrice VARCHAR(100) NOT NULL,
    Genere VARCHAR(100) NOT NULL,
    Pubblicazione DATE NOT NULL,
    Trama TEXT NOT NULL,
    Numero_copie INT DEFAULT 1, -- Numero delle copie disponibili in "magazzino"
    FOREIGN KEY (Autore) REFERENCES Autori(ID_Autore) ON DELETE SET NULL,
    FOREIGN KEY (Genere) REFERENCES Generi(Nome) ON DELETE SET NULL
);

-- Tabella lista prestiti
CREATE TABLE Prestiti (
    ID_Prestito INT AUTO_INCREMENT PRIMARY KEY,
    Cliente INT NOT NULL,
    Libro INT NOT NULL,
    Data_prestito DATE DEFAULT CURRENT_DATE,
    Data_scadenza DATE NOT NULL,
    Data_restituzione DATE NULL,
    FOREIGN KEY (Cliente) REFERENCES Clienti(ID_Cliente) ON DELETE CASCADE,
    FOREIGN KEY (Libro) REFERENCES Libri(ID_Libro) ON DELETE CASCADE
);

-- Tabella Wishlist
CREATE TABLE Wishlist (
    Cliente INT NOT NULL,
    Libro INT NOT NULL,
    PRIMARY KEY (Cliente, Libro),
    FOREIGN KEY (Cliente) REFERENCES Clienti(ID_Cliente) ON DELETE CASCADE,
    FOREIGN KEY (Libro) REFERENCES Libri(ID_Libro) ON DELETE CASCADE
);



