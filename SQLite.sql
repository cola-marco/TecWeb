DROP TABLE IF EXISTS Clienti;
DROP TABLE IF EXISTS Autori;
DROP TABLE IF EXISTS Generi;
DROP TABLE IF EXISTS Libri;
DROP TABLE IF EXISTS Prestiti;
DROP TABLE IF EXISTS Wishlist;

-- Tabella dei Clienti
CREATE TABLE Clienti (
    ID_Cliente INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(150) UNIQUE NOT NULL,
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
    Image_path VARCHAR(50),
    Casa_Editrice VARCHAR(100) NOT NULL,
    Genere VARCHAR(100) NOT NULL,
    Pubblicazione DATE NOT NULL,
    Trama TEXT NOT NULL,
    Numero_copie INT DEFAULT 1, -- Numero delle copie disponibili in "magazzino"
    FOREIGN KEY (Autore) REFERENCES Autori(ID_Autore) ON DELETE RESTRICT,
    FOREIGN KEY (Genere) REFERENCES Generi(Nome) ON DELETE RESTRICT
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

INSERT INTO Autori (Nome, Cognome) VALUES
('Umberto', 'Eco'),
('George', 'Orwell'),
('Jane', 'Austen'),
('J.R.R.', 'Tolkien'),
('Gabriel García', 'Márquez'),
('Antoine de', 'Saint-Exupéry'),
('Fëdor', 'Dostoevskij'),
('Italo', 'Svevo'),
('J.K.', 'Rowling'),
('Jack', 'Kerouac');

INSERT INTO Generi (Nome) VALUES
('Storico'),
('Distopico'),
('Romantico'),
('Fantasy'),
('Realismo Magico'),
('Fiaba'),
('Psicologico'),
('Romanzo introspettivo'),
('Narrativa');


INSERT INTO Libri (Titolo, Autore, Casa_Editrice, Genere, Pubblicazione, Trama) VALUES 
('Il nome della rosa', 1, 'Bompiani', 'Storico', 1980, 'Un monaco francescano indaga su misteriosi omicidi in un monastero medievale, tra segreti e verità scomode.'),
('1984', 2, 'Secker & Warburg', 'Distopico', 1949, 'In un futuro totalitario, un uomo cerca la verità in una società oppressa dal Grande Fratello.'),
('Orgoglio e pregiudizio', 3, 'Thomas Egerton', 'Romantico', 1813, 'La storia di Elizabeth Bennet e del suo difficile rapporto con il signor Darcy, tra orgoglio e pregiudizi.'),
('Il signore degli anelli', 4, 'Allen & Unwin', 'Fantasy', 1954, 'Un gruppo di eroi parte per distruggere un anello magico e salvare la Terra di Mezzo dalle tenebre.'),
('Cento anni di solitudine', 5, 'Sudamericana', 'Realismo Magico', 1967, 'La saga della famiglia Buendía in un villaggio immaginario ricco di eventi magici e simbolici.'),
('Il piccolo principe', 6, 'Reynal & Hitchcock', 'Fiaba', 1943, 'Un aviatore incontra un bambino proveniente da un altro pianeta che gli insegna il senso della vita.'),
('Delitto e castigo', 7, 'The Russian Messenger', 'Psicologico', 1866, 'Un giovane studente commette un omicidio e affronta le conseguenze morali del suo gesto.'),
('La coscienza di Zeno', 8, 'Cappelli', 'Romanzo introspettivo', 1923, 'Il protagonista racconta la propria vita attraverso sedute psicoanalitiche, cercando un senso alla sua esistenza.'),
('Harry Potter e la pietra filosofale', 9, 'Bloomsbury', 'Fantasy', 1997, 'Un ragazzo scopre di essere un mago e inizia la sua avventura nella scuola di magia di Hogwarts.'),
('Sulla strada', 10, 'Viking Press', 'Narrativa', 1957, 'Il racconto di un viaggio on the road negli Stati Uniti, simbolo della beat generation.');

