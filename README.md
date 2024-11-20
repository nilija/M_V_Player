# MiV_Pleyer
Muzički i Video Player

Šta radi ovaj program?
Ovaj program predstavlja interaktivni muzički i video plejer sa sledećim funkcionalnostima:

Prikaz foldera i fajlova:

Pregled foldera i fajlova u zadanom direktorijumu na hijerarhijski način (kao stablo).
Fajlovi sa ekstenzijama .mp3 (audio) i .mp4 (video) su posebno označeni i dostupni za reprodukciju.
Reprodukcija fajlova:

Audio i video fajlovi se mogu reprodukovati direktno iz liste.
Prikazuje naziv trenutno aktivnog fajla i foldera.
Vizuelizacija i ekvilajzer:

Sadrži grafičku vizualizaciju zvuka (ekvilajzer) za audio fajlove.
Kontrole za podešavanje basa, visokih tonova i pojačanja.
Pretraga:

Omogućava korisniku da pretražuje fajlove i foldere po imenu.
Navigacija kroz foldere:

Korisnik može da otvara/zatvara prikaz podfoldera klikom na folder.
Kako koristiti?
Instalacija i pokretanje:

Postavite PHP fajl u korenski direktorijum koji sadrži vaše audio i video fajlove.
Ažurirajte promenljivu $rootDir sa putanjom ka folderu koji želite da pregledate.
Prikaz na stranici:

Pokrenite PHP server i otvorite fajl u web pretraživaču (npr. http://localhost/naziv-fajla.php).
Navigacija i reprodukcija:

Kliknite na ime foldera da biste videli njegov sadržaj.
Kliknite na ime fajla za reprodukciju (audio ili video).
Aktivni fajl se vizualno ističe (crvenom bojom).
Podešavanje zvuka:

Podesite pojačanje (gain), bas (bass) i visoke tonove (treble) koristeći klizače u sekciji ekvilajzera.
Pretraga:

Upišite naziv fajla ili foldera u polje za pretragu kako biste ga pronašli.
Sledeći fajl:

Nakon završetka reprodukcije trenutnog fajla, automatski se prelazi na sledeći fajl u listi.
Napomena:
Program zahteva moderni web pregledač (za vizuelizaciju i audio/video reprodukciju).
PHP server mora imati pristup folderima i fajlovima definisanim u $rootDir.