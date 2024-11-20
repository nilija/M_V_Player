# MiV_Pleyer
Muzički i Video Player

# Uputstvo za korišćenje programa

Ovaj PHP program služi za prikazivanje strukture foldera i fajlova na interaktivan način. Program omogućava pretragu i reprodukciju audio (`.mp3`) i video (`.mp4`) fajlova sa ekvilajzerom.

---

## Funkcionalnosti

1. **Prikaz foldera i fajlova**:
   - Program prikazuje hijerarhijsku strukturu foldera i fajlova.
   - Foldere možete otvarati i zatvarati klikom na ikonicu foldera.

2. **Reprodukcija fajlova**:
   - Klikom na audio ili video fajl pokreće se odgovarajući plejer:
     - **Audio fajlovi**: Prikazuju se u audio plejeru sa opcijama ekvilajzera.
     - **Video fajlovi**: Prikazuju se u video plejeru.

3. **Pretraga**:
   - Omogućena je pretraga fajlova i foldera pomoću pretraživačke trake.

4. **Ekvilajzer**:
   - Kontrole za podešavanje jačine zvuka (Gain), basa (Bass), i visokih tonova (Treble).

5. **Automatska reprodukcija**:
   - Kada fajl završi, program automatski prelazi na sledeći fajl u listi.

---

## Kako koristiti program

1. **Postavite direktorijum sa fajlovima**:
   - U PHP kodu zamenite vrednost promenljive `$rootDir` putanjom do vašeg foldera:
     ```php
     $rootDir = './Putanja_do_foldera';
     ```

2. **Pokrenite aplikaciju**:
   - Sačuvajte kod u `.php` fajl i otvorite ga u pregledaču putem lokalnog servera (npr. XAMPP, WAMP).

3. **Navigacija kroz foldere**:
   - Kliknite na ikonu foldera da biste prikazali sadržaj.
   - Klikom na ime fajla pokrećete reprodukciju.

4. **Pretražujte fajlove**:
   - Unesite ključnu reč u polje za pretragu na vrhu stranice.

5. **Koristite ekvilajzer**:
   - Podesite parametre reprodukcije (Gain, Bass, Treble) koristeći klizače.

---

## Struktura koda

### 1. **PHP funkcija**: `prikaziFoldereIFajlove`
- Rekurzivno prolazi kroz foldere i fajlove do određenog nivoa.
- Prikazuje foldere i fajlove koji zadovoljavaju kriterijume (npr. ekstenzija `.mp3` i `.mp4`).

### 2. **HTML deo**:
- Sadrži elemente za prikaz foldera, plejera i kontrole ekvilajzera.

### 3. **JavaScript deo**:
- Implementira funkcionalnosti kao što su:
  - Reprodukcija fajlova.
  - Prikaz trenutnog fajla.
  - Pretraga.
  - Kontrola ekvilajzera.

---

## Primer korišćenja

1. Struktura foldera:

MOMO/ ├── folder1/ │ ├── pesma1.mp3 │ ├── pesma2.mp3 ├── folder2/ ├── video1.mp4

2. Pretraga:
- Unesite "pesma" u pretraživač da biste pronašli sve fajlove sa tom ključnom reči.

3. Reprodukcija:
- Kliknite na fajl `pesma1.mp3` da biste pokrenuli reprodukciju u audio plejeru.

---

## Podešavanja i prilagođavanja

1. **Prilagodite nivo rekurzije**:
- Izmenite vrednost `$maksimalniNivo` u funkciji `prikaziFoldereIFajlove`.

2. **Podržane ekstenzije**:
- Dodajte ili izmenite uslov za podržane fajlove u PHP kodu:
  ```php
  if (preg_match('/\.(mp3|mp4|wav)$/i', $entry)) {
      // Vaša logika
  }
  ```

3. **Izgled stranice**:
- Prilagodite CSS stilove prema vašim potrebama.

---

## Zahtevi

- **PHP server**: Lokalni server kao što su XAMPP, WAMP ili bilo koji PHP server.
- **Pregledač**: Moderan pregledač sa podrškom za HTML5 i JavaScript.
- **Fajlovi**: Audio (`.mp3`) i video (`.mp4`) fajlovi za reprodukciju.

---

## Napomena

- U slučaju problema sa reprodukcijom, proverite da li je putanja do fajlova tačno postavljena.
- Ako se ne prikazuju svi folderi i fajlovi, povećajte vrednost `$maksimalniNivo`.
