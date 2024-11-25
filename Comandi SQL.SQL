/* Comandi SQL */

/*Creazione DB*/

CREATE DATABASE Esame_Basi_Di_Dati;

/*Creazione Schema*/

CREATE SCHEMA Universita;

/*Creazione funzione inserimento identificativi*/

CREATE OR REPLACE FUNCTION Universita.genera_identificativo() RETURNS VARCHAR AS $$
DECLARE
  random_value VARCHAR;
BEGIN
  random_value := substr(md5(random()::text), 1, 6);
  RETURN random_value;
END;
$$ LANGUAGE plpgsql;

/*Creazione Tabelle*/

CREATE TABLE Universita.Docente (
    id char (6) PRIMARY KEY DEFAULT Universita.genera_identificativo(),
    nome varchar (15),
    cognome varchar (15),
    data_di_nascita DATE,
    telefono varchar (10) UNIQUE,
    email varchar (50) UNIQUE,
    via varchar (50),
    cap char (5),
    civico varchar(4),
    password varchar(32)
);

/*--------*/

CREATE TABLE Universita.Studente(
    matricola char (6) PRIMARY KEY DEFAULT Universita.genera_identificativo(),
    nome varchar (15),
    cognome varchar (15),
    data_di_nascita DATE,
    telefono varchar (10) UNIQUE,
    email varchar (50) UNIQUE,
    via varchar (50),
    cap char (5),
    civico varchar(4),
    id_carriera SERIAL UNIQUE,
    id_corso int,
    password varchar(32)
);

/*--------*/

CREATE TABLE Universita.Segreteria(
    id char (6) PRIMARY KEY DEFAULT Universita.genera_identificativo(),
    nome varchar (15),
    cognome varchar (15),
    data_di_nascita DATE,
    telefono varchar (10) UNIQUE,
    email varchar (50) UNIQUE,
    via varchar (50),
    cap char (5),
    civico varchar(4),
    password varchar(32)
);

/*--------*/

CREATE TABLE Universita.Indirizzo(
    cap char (5) PRIMARY KEY,
    citta varchar(15)
);

/*--------*/

CREATE TABLE Universita.esame(
    id_insegnamento int,
    data DATE,
    ora TIME,
    tipologia varchar(11),
    id_docente char(6),
    nome_esame varchar(100),
    PRIMARY KEY(id_insegnamento, data)
);

/*--------*/

CREATE TABLE Universita.trascrizione(
    id SERIAL PRIMARY KEY,
    id_insegnamento int,
    data DATE,
    id_carriera int,
    voto int check (voto>=0 and voto <=30),
    esito BOOLEAN
);

/*--------*/

CREATE TABLE Universita.corso_di_laurea(
    id SERIAL PRIMARY KEY,
    tipologia bool,
    durata char (1),
    id_docente char (6) UNIQUE NULL,
    nome varchar(50)
);

/*--------*/

CREATE TABLE Universita.Storico_Studente(
    matricola char (6) PRIMARY KEY DEFAULT Universita.genera_identificativo(),
    nome varchar (15),
    cognome varchar (15),
    data_di_nascita DATE,
    telefono varchar (10) UNIQUE,
    email varchar (50) UNIQUE,
    via varchar (50),
    cap char (5),
    civico varchar(4),
    id_storico_carriera int UNIQUE,
    password varchar(32)
);

/*--------*/

/* CREATE TABLE Universita.storico_esame(
    id_insegnamento int,
    data DATE,
    ora TIME,
    tipologia varchar(11),
    id_docente char(6),
    nome_esame varchar(100),
    PRIMARY KEY(id_insegnamento, data)
); */

/*--------*/

CREATE TABLE universita.curriculum(
    id SERIAL PRIMARY KEY,
    id_insegnamento int,
    data DATE,
    id_carriera int,
    nome_esame varchar(100),
    voto int check (voto>=0 and voto <=30),
    esito BOOLEAN
);

/*--------*/

CREATE TABLE universita.insegnamento(
    id SERIAL PRIMARY KEY,
    anno int CHECK (anno >= 1 and anno <=3),
    id_docente_responsabile char (6),
    nome varchar (30)
);

/*--------*/

/*Funzione che verifica se il docente è responsabile al massimo di 3 insegnamenti*/

CREATE OR REPLACE FUNCTION universita.verifica_docenti_3(docente_id char(6)) RETURNS BOOLEAN AS $$
DECLARE
    num_corsi INT;
BEGIN
    SELECT COUNT(*)
    INTO num_corsi
    FROM universita.insegnamento
    WHERE id_docente_responsabile = docente_id;

    RETURN num_corsi < 3;
END;
$$ LANGUAGE plpgsql;

/*--------*/

ALTER TABLE universita.insegnamento
ADD CONSTRAINT max_3_corsi
CHECK (universita.verifica_docenti_3(id_docente_responsabile));

/*--------*/

CREATE TABLE universita.programma(
    id_insegnamento int REFERENCES universita.insegnamento(id),
    id_corso_di_laurea int REFERENCES universita.corso_di_laurea(id),
    PRIMARY KEY (id_insegnamento, id_corso_di_laurea)
);


/*--------*/

CREATE TABLE universita.incarico(
    id_docente char (6),
    id_insegnamento int,
    PRIMARY KEY (id_docente, id_insegnamento)

);

/*--------*/

CREATE TABLE universita.propedeutico(
    id_insegnamento1 int,
    id_insegnamento2 int,
    PRIMARY KEY (id_insegnamento1, id_insegnamento2)

);

/*--------*/

CREATE TABLE universita.prenotante(
    matricola_studente char(6),
    id_insegnamento int,
    data date,
    PRIMARY KEY (matricola_studente, id_insegnamento, data)

);

/*--------*/

/*Aggiunta chiavi esterne*/

ALTER TABLE universita.docente
ADD CONSTRAINT chiaveCAP
FOREIGN KEY (cap) REFERENCES universita.indirizzo(cap) ON UPDATE CASCADE ON DELETE NO ACTION;

/*--------*/

ALTER TABLE universita.studente
ADD CONSTRAINT chiaveCAP
FOREIGN KEY (cap) REFERENCES universita.indirizzo(cap) ON UPDATE CASCADE ON DELETE NO ACTION;

/*--------*/

ALTER TABLE universita.studente
ADD CONSTRAINT corsoS
FOREIGN KEY (id_corso) REFERENCES universita.corso_di_laurea(id) ON UPDATE CASCADE ON DELETE NO ACTION;

/*--------*/

ALTER TABLE universita.segreteria
ADD CONSTRAINT chiaveCAP
FOREIGN KEY (cap) REFERENCES universita.indirizzo(cap) ON UPDATE CASCADE ON DELETE NO ACTION;

/*--------*/

ALTER TABLE universita.storico_studente
ADD CONSTRAINT chiaveCAP
FOREIGN KEY (cap) REFERENCES universita.indirizzo(cap) ON UPDATE CASCADE ON DELETE NO ACTION;

/*--------*/

ALTER TABLE universita.esame
ADD CONSTRAINT chiaveInsegnamento
FOREIGN KEY (id_insegnamento) REFERENCES universita.insegnamento(id) ON UPDATE CASCADE ON DELETE SET NULL;

/*--------*/

ALTER TABLE universita.esame
ADD CONSTRAINT chiaveDocente
FOREIGN KEY (id_docente) REFERENCES universita.docente(id) ON UPDATE CASCADE ON DELETE SET NULL;

/*--------*/

ALTER TABLE universita.corso_di_laurea
ADD CONSTRAINT chiaveDocente
FOREIGN KEY (id_docente) REFERENCES universita.docente(id) ON UPDATE CASCADE ON DELETE SET NULL;

/*--------*/

ALTER TABLE universita.trascrizione
ADD CONSTRAINT chiaveEsame
FOREIGN KEY (id_insegnamento, data) REFERENCES universita.esame(id_insegnamento, data) ON UPDATE CASCADE ON DELETE NO ACTION;

/*--------*/

ALTER TABLE universita.trascrizione
ADD CONSTRAINT chiaveCarriera
FOREIGN KEY (id_carriera) REFERENCES universita.studente(id_carriera) ON UPDATE CASCADE ON DELETE CASCADE;

/*--------*/


ALTER TABLE universita.curriculum
ADD CONSTRAINT chiaveCStoricoCarriera
FOREIGN KEY (id_carriera) REFERENCES universita.storico_studente(id_storico_carriera);

/*--------*/

/* ALTER TABLE universita.curriculum
ADD CONSTRAINT chiaveStoricoEsami
FOREIGN KEY (id_storico_esami) REFERENCES universita.storico_esame(id); */

/*------*/
/*------*/
ALTER TABLE universita.insegnamento
ADD CONSTRAINT chiaveDocente
FOREIGN KEY (id_docente_responsabile) REFERENCES universita.docente(id) ON UPDATE CASCADE ON DELETE SET NULL;

/*--------*/

ALTER TABLE universita.incarico
ADD CONSTRAINT chiaveDocente
FOREIGN KEY (id_docente) REFERENCES universita.docente(id) ON UPDATE CASCADE ON DELETE SET NULL;

/*--------*/

ALTER TABLE universita.incarico
ADD CONSTRAINT chiaveInsegnamento
FOREIGN KEY (id_insegnamento) REFERENCES universita.insegnamento(id) ON UPDATE CASCADE ON DELETE CASCADE;

/*--------*/

ALTER TABLE universita.propedeutico
ADD CONSTRAINT chiaveInsegnamento1
FOREIGN KEY (id_insegnamento1) REFERENCES universita.insegnamento(id) ON UPDATE CASCADE ON DELETE CASCADE;

/*--------*/

ALTER TABLE universita.propedeutico
ADD CONSTRAINT chiaveInsegnamento2
FOREIGN KEY (id_insegnamento2) REFERENCES universita.insegnamento(id) ON DELETE CASCADE ON UPDATE CASCADE;

/*--------*/

ALTER TABLE universita.prenotante
ADD CONSTRAINT chiaveMatricola
FOREIGN KEY (matricola_studente) REFERENCES universita.studente(matricola) ON DELETE CASCADE ON UPDATE CASCADE;

/*--------*/

ALTER TABLE universita.prenotante
ADD CONSTRAINT chiaveEsame
FOREIGN KEY (id_insegnamento, data) REFERENCES universita.esame(id_insegnamento, data);

/*--------*/

/*Creazione funzioni e trigger*/

/*Funzione che imposta la durata del corso di laurea in base al tipo del corso*/

CREATE OR REPLACE FUNCTION universita.imposta_durata()
RETURNS trigger AS $$
BEGIN
    IF NEW.tipologia THEN
        NEW.durata := '3';
    ELSE
        NEW.durata := '2';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che richiama la funzione imposta_durata() ad ogni inserimento*/

CREATE TRIGGER imposta_durata_trigger
BEFORE INSERT ON universita.corso_di_laurea
FOR EACH ROW
EXECUTE FUNCTION universita.imposta_durata();

/*--------*/

/*Funzione che imposta l'esito a true se il voto>=18 o a false se il voto<18 */

CREATE OR REPLACE FUNCTION universita.calcola_esito()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.voto >= 18 THEN
        NEW.esito = true;
    ELSE
        NEW.esito = false;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che richiama la funzione calcola_esito() ad ogni inserimento */

CREATE TRIGGER trigger_calcola_esito
BEFORE INSERT ON universita.trascrizione
FOR EACH ROW
EXECUTE FUNCTION universita.calcola_esito();

/*--------*/

/*Funzione che trasferisce i dati di uno studente nella tabella storico_studente */

CREATE OR REPLACE FUNCTION universita.sposta_studente()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO universita.storico_studente (matricola, nome, cognome, data_di_nascita, telefono, email, via, civico, cap, id_storico_carriera, password)
    SELECT OLD.matricola, OLD.nome, OLD.cognome, OLD.data_di_nascita, OLD.telefono, OLD.email, OLD.via, OLD.civico, OLD.cap, OLD.id_carriera, OLD.password
    FROM universita.studente
    WHERE studente.matricola = OLD.matricola;

    INSERT INTO universita.curriculum (id_carriera, data, voto, esito, nome_esame, id_insegnamento)
    SELECT trascrizione.id_carriera, trascrizione.data, trascrizione.voto, trascrizione.esito, esame.nome_esame, trascrizione.id_insegnamento
    FROM universita.trascrizione INNER JOIN universita.esame ON esame.id_insegnamento = trascrizione.id_insegnamento AND esame.data = trascrizione.data
    INNER JOIN universita.studente ON trascrizione.id_carriera = studente.id_carriera
    WHERE studente.id_carriera = OLD.id_carriera;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che avvia la funzione sposta_studenti() sulla delete di uno studente */

CREATE TRIGGER trigger_sposta_esami
BEFORE DELETE ON universita.studente
FOR EACH ROW
EXECUTE FUNCTION universita.sposta_studente();

/*---------------------------------------------------------------------------*/

/*Funzione che verifica la presenza di esami propedeutici non passati prima di consentire la prenotazione */

CREATE OR REPLACE FUNCTION universita.esami_prop() RETURNS TRIGGER AS $$

DECLARE

    corso_studente int;
    esito BOOLEAN;
    stesso_corso int;
    insegnamento int;
    ultima_data date;


DECLARE cursore CURSOR FOR
    SELECT i2.id
    FROM universita.esame e1 INNER JOIN universita.insegnamento i1
    ON e1.id_insegnamento = i1.id INNER JOIN universita.propedeutico p
    ON i1.id=p.id_insegnamento1 INNER JOIN universita.insegnamento i2
    ON p.id_insegnamento2 = i2.id INNER JOIN universita.programma pr
    ON i2.id = pr.id_insegnamento INNER JOIN universita.esame e2
    ON i2.id = e2.id_insegnamento
    WHERE pr.id_corso_di_laurea = corso_studente;
    
BEGIN
    SELECT s.id_corso
    FROM universita.studente s
    WHERE s.matricola = NEW.matricola_studente
    INTO corso_studente;

    SELECT COUNT(*)
    FROM universita.esame e1 INNER JOIN universita.insegnamento i1
    ON e1.id_insegnamento = i1.id INNER JOIN universita.propedeutico p
    ON i1.id=p.id_insegnamento1 INNER JOIN universita.insegnamento i2
    ON p.id_insegnamento2 = i2.id INNER JOIN universita.programma pr
    ON i2.id = pr.id_insegnamento
    WHERE pr.id_corso_di_laurea = corso_studente
    INTO stesso_corso;

    IF (stesso_corso > 0) THEN
        
        OPEN cursore;

        FETCH NEXT FROM cursore INTO insegnamento;
        WHILE FOUND LOOP
            SELECT MAX(t.data)
            FROM universita.trascrizione t
            WHERE t.id_insegnamento = insegnamento
            INTO ultima_data;

            SELECT t.esito
            FROM universita.trascrizione t
            WHERE t.id_insegnamento = insegnamento AND t.data = ultima_data
            INTO esito;

            IF NOT esito OR esito is null THEN
                RAISE EXCEPTION 'Impossibile iscriversi non sono stati sueprati gli esami propedeutici';
            END IF;

            FETCH NEXT FROM cursore INTO insegnamento;
        END LOOP;

        CLOSE cursore;
    END IF;
    
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che richiama la funzione di verifica esami_prop()*/

CREATE OR REPLACE TRIGGER check_prop
BEFORE INSERT ON universita.prenotante
FOR EACH ROW
EXECUTE FUNCTION universita.esami_prop();

/*--------*/

/*Funzione che verifica se l'insegnamento che sto inserendo è ad un anno compatibile con la durata del corso*/

CREATE OR REPLACE FUNCTION universita.anno_insegnamento() RETURNS TRIGGER AS $$

DECLARE
    anno_previsto int;
    corso_appartenente BOOLEAN;

BEGIN

    SELECT insegnamento.anno
    FROM universita.insegnamento
    WHERE insegnamento.id = new.id_insegnamento
    INTO anno_previsto;

    SELECT cdl.tipologia
    FROM universita.corso_di_laurea cdl
    WHERE cdl.id = NEW.id_corso_di_laurea
    INTO corso_appartenente;

    IF (anno_previsto > 2 AND NOT corso_appartenente) THEN
        RAISE EXCEPTION 'Operazione fallita, non si può inserire un insegnamento al 3° anno di un corso che ha 2 anni';
    END IF;

    RETURN NEW;

END

$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che ad ogni inserimento di un insegnamento in un corso richiama la funzione anno_insegnamento() che verifica la compatbilita tra durata del corso ed anno dell'insegnamento*/

CREATE OR REPLACE TRIGGER check_anno
BEFORE INSERT OR UPDATE ON universita.programma
FOR EACH ROW
EXECUTE FUNCTION universita.anno_insegnamento();

/*--------*/

/*Funzione che verifica se uno studente non ha prenotato altri esami nella stessa data*/

CREATE OR REPLACE FUNCTION universita.stessa_data() RETURNS TRIGGER AS $$

DECLARE
    data_esame DATE;
    esami_in_data int;

BEGIN

    SELECT e.data
    FROM universita.esame e
    WHERE e.id_insegnamento=NEW.id_insegnamento AND e.data = NEW.data
    INTO data_esame;

    SELECT COUNT(*)
    FROM universita.studente s INNER JOIN universita.prenotante p
    ON s.matricola = p.matricola_studente INNER JOIN universita.esame e
    ON p.id_insegnamento = e.id_insegnamento AND p.data = e.data
    WHERE s.matricola = NEW.matricola_studente AND p.data = data_esame AND p.id_insegnamento<> NEW.id_insegnamento
    INTO esami_in_data;

    IF (esami_in_data > 0) THEN
        RAISE EXCEPTION 'Operazione fallita, già presente un esame in quella data';
    END IF;

    RETURN NEW;

END

$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che ad ogni prenotazione di un esame verifica se lo studente non ha già prenotato un esame nella stessa data chiamando la funzione stessa_data()*/

CREATE OR REPLACE TRIGGER check_stessa_data
BEFORE INSERT OR UPDATE ON universita.prenotante
FOR EACH ROW
EXECUTE FUNCTION universita.stessa_data();

/*--------*/

/*Funzione che verifica se due insegnamenti propedeutici hanno un corso di laurea in comune*/

CREATE OR REPLACE FUNCTION universita.verifica_corso_di_laurea_comune()
RETURNS TRIGGER AS $$
DECLARE
    corso_insegnamento1 INT;
    corso_insegnamento2 INT;
    anno1 int;
    anno2 int;
BEGIN

    SELECT id_corso_di_laurea
    INTO corso_insegnamento1
    FROM universita.programma
    WHERE id_insegnamento = NEW.id_insegnamento1;

    SELECT id_corso_di_laurea
    INTO corso_insegnamento2
    FROM universita.programma
    WHERE id_insegnamento = NEW.id_insegnamento2;

    IF corso_insegnamento1 = ANY (ARRAY[corso_insegnamento2]) OR corso_insegnamento2 = ANY (ARRAY[corso_insegnamento1]) THEN
        SELECT i.anno 
        FROM universita.insegnamento i
        WHERE i.id = NEW.id_insegnamento1
        INTO anno1;

        SELECT i.anno 
        FROM universita.insegnamento i
        WHERE i.id = NEW.id_insegnamento2
        INTO anno2;

        IF anno1 > anno2 THEN
            RAISE EXCEPTION 'L''insegnamento necessario è effettuato in un anno successivo, IMPOSSIBILE';
        END IF;

        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Gli insegnamenti non hanno un corso di laurea in comune.';
    END IF;
END;
$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che ad ogni inserimento di propedeuticità tra insegnamenti richiama la funzione verifica_corso_di_laurea_comune()*/

CREATE TRIGGER verifica_corso_di_laurea_trigger
BEFORE INSERT ON universita.propedeutico
FOR EACH ROW
EXECUTE FUNCTION universita.verifica_corso_di_laurea_comune();

/*--------*/

/*Funzione che verifica se l'esame fa parte del corso a cui lo studente è iscritto*/

CREATE OR REPLACE FUNCTION universita.verifica_corso_studente()
RETURNS TRIGGER AS $$
DECLARE
    corso_studente INT;
    corso_insegnamento INT;

BEGIN

    SELECT s.id_corso
    FROM universita.studente s
    WHERE s.matricola = NEW.matricola_studente
    INTO corso_studente;

    SELECT id_corso_di_laurea
    FROM universita.programma
    WHERE id_insegnamento = NEW.id_insegnamento
    INTO corso_insegnamento;

    IF corso_studente = ANY (ARRAY[corso_insegnamento]) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Lo studente non fa parte di nessun corso di laurea in cui è previsto quell''insegnamento';
    END IF;
END;
$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che ad ogni prenotazione richiama la funzione verifica_corso_studente()*/

CREATE TRIGGER check_corso_studente_trigger
BEFORE INSERT ON universita.prenotante
FOR EACH ROW
EXECUTE FUNCTION universita.verifica_corso_studente();

/*--------*/

/*Funzione che verifica se ci sono altri esami dello stesso anno nella stessa data in cui si sta inserendo un nuovo esame */

CREATE OR REPLACE FUNCTION universita.data_esame_stesso_anno()
RETURNS TRIGGER AS $$
DECLARE
    year INT;
    corso_insegnamento1 INT;
    corso_insegnamento2 INT;
    insegnamento INT;

DECLARE cursore CURSOR FOR
    SELECT e.id_insegnamento
    FROM universita.esame e INNER JOIN universita.insegnamento i
    ON e.id_insegnamento = i.id
    WHERE e.data = NEW.data AND i.anno = year;

BEGIN

    SELECT i.anno
    FROM universita.insegnamento i
    WHERE i.id = NEW.id_insegnamento
    INTO year;

    OPEN cursore;
    FETCH NEXT FROM cursore INTO insegnamento;

    WHILE FOUND LOOP
        SELECT id_corso_di_laurea
        INTO corso_insegnamento1
        FROM universita.programma
        WHERE id_insegnamento = NEW.id_insegnamento;
        
        SELECT id_corso_di_laurea
        INTO corso_insegnamento2
        FROM universita.programma
        WHERE id_insegnamento = insegnamento;

        IF corso_insegnamento1 = ANY (ARRAY[corso_insegnamento2]) OR corso_insegnamento2 = ANY (ARRAY[corso_insegnamento1])  THEN
            RAISE EXCEPTION 'Sono già presenti esami dello stesso anno per quella data';
        END IF;

        FETCH NEXT FROM cursore INTO insegnamento;
    END LOOP;
    CLOSE cursore;

    RETURN NEW; 
END;
$$ LANGUAGE plpgsql;

/*--------*/

/*Trigger che ad ogni inserimento di un esame richiama la funzione data_esame_stesso_anno()*/

CREATE TRIGGER verifica_esami_data_trigger
BEFORE INSERT ON universita.esame
FOR EACH ROW
EXECUTE FUNCTION universita.data_esame_stesso_anno();

/*--------*/

CREATE OR REPLACE FUNCTION universita.carriera_completa(matricolaP CHAR(6))
RETURNS TABLE (
    nome_esame varchar(100),
    esito BOOLEAN,
    voto INT,
    data date
)

AS $$

BEGIN
    RETURN QUERY
    SELECT e.nome_esame, t.esito, t.voto, t.data
    FROM universita.esame e INNER JOIN universita.trascrizione t
    ON e.id_insegnamento = t.id_insegnamento AND e.data = t.data
    INNER JOIN universita.studente s ON s.id_carriera = t.id_carriera
    WHERE s.matricola = matricolaP;
END;
$$ LANGUAGE plpgsql;

/*--------*/

CREATE OR REPLACE FUNCTION universita.carriera_valida(matricolaP CHAR(6))
RETURNS TABLE (
    nome_esame varchar(100),
    esito BOOLEAN,
    voto INT,
    data date
)

AS $$
BEGIN
    RETURN QUERY (
        WITH dataMassima AS (
            SELECT t.id_carriera, t.id_insegnamento, MAX(t.DATA) as data
            FROM universita.trascrizione t
            GROUP BY id_carriera, id_insegnamento
        )
        SELECT e.nome_esame, t.esito, t.voto, t.data
        FROM universita.esame e
        INNER JOIN universita.trascrizione t ON e.id_insegnamento = t.id_insegnamento AND e.data = t.data
        INNER JOIN universita.studente s ON s.id_carriera = t.id_carriera 
        INNER JOIN dataMassima d ON t.id_insegnamento = d.id_insegnamento AND t.data = d.data
        WHERE s.matricola = matricolaP AND t.esito
    );
END;
$$ LANGUAGE plpgsql;


/*--------*/

CREATE OR REPLACE FUNCTION universita.insegnamenti_corso(nome VARCHAR(50))
RETURNS TABLE (
    nome_Insegnamento varchar(30),
    nome_docente varchar(15),
    cognome_docente varchar(15),
    anno INT
)

AS $$


BEGIN
    RETURN QUERY
    SELECT i.nome, d.nome, d.cognome, i.anno
    FROM universita.insegnamento i INNER JOIN universita.docente d
    ON i.id_docente_responsabile = d.id
    INNER JOIN universita.programma p ON p.id_insegnamento = i.id
    INNER JOIN universita.corso_di_laurea c ON p.id_corso_di_laurea = c.id
    WHERE c.nome = nome;
END;
$$ LANGUAGE plpgsql;

/*--------*/


