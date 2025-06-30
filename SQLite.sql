DROP TABLE IF EXISTS Recensioni;
DROP TABLE IF EXISTS Whishlist;
DROP TABLE IF EXISTS Prestiti;
DROP TABLE IF EXISTS Libri;
DROP TABLE IF EXISTS Clienti;


-- Tabella dei Clienti
CREATE TABLE Clienti (
    ID_Cliente INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Username VARCHAR(100) UNIQUE NOT NULL,
    Pass VARCHAR(100) NOT NULL,
    Ruolo ENUM('Cliente', 'Admin') DEFAULT 'Cliente'
);

-- Tabella lista Libri
CREATE TABLE Libri (
    ID_Libro INT AUTO_INCREMENT PRIMARY KEY,
    Titolo VARCHAR(100) NOT NULL,
    Autore VARCHAR(100) NOT NULL,
    Image_path VARCHAR(50),
    Casa_Editrice VARCHAR(100) NOT NULL,
    Genere VARCHAR(100) NOT NULL,
    Pubblicazione INT NOT NULL,
    Trama TEXT NOT NULL
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


CREATE TABLE Recensioni (
    Cliente INT NOT NULL,
    Libro INT NOT NULL,
    Valutazione INT NOT NULL,
    Recensione TEXT,
    Data TIMESTAMP,
    Segnala BOOLEAN,
    PRIMARY KEY (Cliente, Libro),
    FOREIGN KEY (Cliente) REFERENCES Clienti(ID_Cliente) ON DELETE CASCADE,
    FOREIGN KEY (Libro) REFERENCES Libri(ID_Libro) ON DELETE CASCADE
);


INSERT INTO Libri (Titolo, Autore, Image_path, Casa_Editrice, Genere, Pubblicazione, Trama) VALUES 
('Il nome della rosa', 'Umberto Eco', 'Immagini/cover_68484e26e00496.19349912.jpg', 'Bompiani', 'Storico', 1980, 'Un monaco francescano indaga su misteriosi omicidi in un monastero medievale, tra segreti e verità scomode. Attraverso logica e osservazione, cerca di scoprire la causa di una serie di morti misteriose legate a manoscritti proibiti e lotte di potere all’interno dell’abbazia.'),
('1984', 'George Orwell', 'Immagini/cover_68484e68a8e416.87839484.jpg', 'Secker & Warburg', 'Distopico', 1949, 'In un futuro totalitario, un uomo cerca la verità in una società oppressa dal Grande Fratello. La sua ribellione, motivata dall’amore e dal desiderio di libertà, lo porta a scoprire quanto sia implacabile il controllo del regime.'),
('Orgoglio e pregiudizio', 'Jane Austin', 'Immagini/cover_68484e8a0269f4.65233254.jpg', 'Thomas Egerton', 'Romantico', 1813, 'La storia di Elizabeth Bennet e del suo difficile rapporto con il signor Darcy, tra orgoglio e pregiudizi. In un intreccio di malintesi e cambiamenti interiori, i due protagonisti imparano a superare le apparenze per trovare il vero amore.'),
('Il signore degli anelli', 'J.R.R. Tolkien', 'Immagini/cover_68484f9b4c4193.78006937.jpg', 'Allen & Unwin', 'Fantasy', 1954, 'Un gruppo di eroi parte per distruggere un anello magico e salvare la Terra di Mezzo dalle tenebre. Attraversano terre incantate e pericolose, affrontando creature oscure e battaglie epiche in una lotta contro il potere assoluto.'),
('Cento anni di solitudine', 'Gabriel Garcìa Màrquez', 'Immagini/cover_68484fb606fe50.94536858.jpg', 'Sudamericana', 'Realismo Magico', 1967, 'La saga della famiglia Buendía in un villaggio immaginario ricco di eventi magici e simbolici. Ogni generazione affronta amori, solitudini e destini ripetuti in un ciclo che fonde il mito con la realtà storica del continente latinoamericano.'),
('Il piccolo principe', 'Antoine de Saint-Exupéry', 'Immagini/cover_68484fc3304cc8.11359000.jpg', 'Reynal & Hitchcock', 'Fiaba', 1943, 'Un aviatore incontra un bambino proveniente da un altro pianeta che gli insegna il senso della vita. Attraverso pianeti, personaggi e metafore, si rivela una profonda riflessione sull’infanzia, l’amicizia e le cose invisibili agli occhi.'),
('Delitto e castigo', 'Fëdor Dostoevskij', 'Immagini/cover_68484fce60e470.93876299.jpg', 'The Russian Messenger', 'Psicologico', 1866, 'Un giovane studente commette un omicidio e affronta le conseguenze morali del suo gesto. Travolto dal rimorso e dalla paura, cerca redenzione attraverso il tormento interiore e l’incontro con la spiritualità cristiana.'),
('La coscienza di Zeno', 'Italo Svevo', 'Immagini/cover_68484fdb11a3e1.36429587.jpg', 'Cappelli', 'Romanzo introspettivo', 1923, 'Il protagonista racconta la propria vita attraverso sedute psicoanalitiche, cercando un senso alla sua esistenza. Tra ironia e autoanalisi, emerge un ritratto contraddittorio dell’uomo moderno, incapace di cambiare davvero.'),
('Harry Potter e la pietra filosofale', 'J.K. Rowling', 'Immagini/cover_68484fe49c6423.11389143.jpg', 'Bloomsbury', 'Fantasy', 1997, 'Un ragazzo scopre di essere un mago e inizia la sua avventura nella scuola di magia di Hogwarts. Tra lezioni, nuove amicizie e nemici nascosti, scopre la verità sulla sua origine e la minaccia che grava sul mondo magico.'),
('Sulla strada', 'Jack Kerouac', 'Immagini/cover_68484fee970171.95685111.jpg', 'Viking Press', 'Narrativa', 1957, 'Il racconto di un viaggio on the road negli Stati Uniti, simbolo della beat generation. Esperienze, incontri e città diventano il pretesto per esplorare una generazione ribelle in cerca di libertà e significato.');

INSERT INTO Clienti(Email, Username, Pass, Ruolo) VALUES
('admin@admin.it', 'admin', '$2y$10$ARIGeQxm1RFyeBjR8mZDm.xtp5iQadKdMXr2.htPfACAYpgpjelwW', 'Admin'),
('user@user.it', 'user', '$2y$10$/YVWfolAqO88eUS5XCaKoOLNumtHk8/kOf8X6NkYBBDyW2hyY16de', 'Cliente');