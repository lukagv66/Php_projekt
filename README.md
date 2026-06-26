# Aplikacija za Upravljanje Nogometnim Klubom

Ovo je web aplikacija za upravljanje nogometnim klubom razvijena u sklopu projekta iz kolegija Osnove Php-a. Sustav omogućuje autorizaciju korisnika, kontrolu pristupa na temelju uloga (trener i navijač), te potpuno upravljanje podacima o igračima i rasporedu utakmica.

Tijekom izrade aplikacije korišten je objektni pristup za rad s bazom podataka, zaštita lozinki i osjetljivih podataka, pripremljeni upiti, php sesije pomoću kojih se prati stanje prijavljenog korisnika i validacija formi gdje sva polja prolaze kroz serversku provjeru prije upisa u bazu. 

## Podaci za testiranje

Unutar sustava pripremljena su dva testna računa s različitim razinama ovlasti kako bi se mogla testirati kontrola pristupa po ulogama

### 1. Trener
Korisničko ime: `admin`
Lozinka:`123456`
*Prava pristupa:* Puni pristup nadzornoj ploči, dodavanje, uređivanje i brisanje igrača te rasporeda utakmica.

### 2. Navijač
Korisničko ime:`navijac`
Lozinka:`1234567`
Prava pristupa:* Pregled nadzorne ploče, pregled statistike igrača i rasporeda utakmica (bez mogućnosti izmjena).


## Struktura Projekta

nogometni_klub/
│
├── config/
│   └── database.php      # Povezivanje s MySQL bazom (PDO)
│
├── register.php          # Registracija novih korisnika
├── login.php             # Prijava u sustav i provjera lozinke
├── logout.php            # Sigurna odjava i uništavanje sesije
├── dashboard.php         # Interaktivni panel prilagođen ulogama
│
├── players.php           # CRUD - Pregled, dodavanje i brisanje igrača
├── edit_player.php       # CRUD - Uređivanje podataka o igraču
│
├── matches.php           # CRUD - Pregled, dodavanje i brisanje utakmica
├── edit_match.php         # CRUD - Uređivanje rasporeda utakmica
│
└── README.md             # Dokumentacija projekta