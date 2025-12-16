Konferenční systém – správa článků a recenzí

Semestrální projekt vytvořený v rámci předmětu WEB.
Aplikace slouží ke správě konferenčních příspěvků (PDF), uživatelů a recenzního řízení.


POPIS PROJEKTU

Webová aplikace simuluje jednoduchý konferenční systém, ve kterém:
- autoři nahrávají články ve formátu PDF,
- recenzenti hodnotí články a rozhodují o jejich přijetí,
- administrátoři spravují uživatele a obsah systému.

Projekt je zaměřen na práci s uživatelskými rolemi, databází a bezpečným uploadem souborů.


POUŽITÉ TECHNOLOGIE

- PHP 8.5
- MySQL / MariaDB
- HTML5, CSS3 (Bootstrap)
- JavaScript (základní použití)
- PDO (bezpečná práce s databází)
- Git & GitHub


ARCHITEKTURA APLIKACE

Aplikace využívá jednoduchou MVC architekturu:
- Model – práce s databází (uživatelé, články, recenze)
- Controller – zpracování požadavků a aplikační logika
- View – šablony pro zobrazení dat uživateli


ADRESÁŘOVÁ STRUKTURA

src/
controllers/ – aplikační logika jednotlivých částí
models/ – databázové modely

view/ – zobrazovací šablony

partials/ – společné části stránek (hlavička, menu, patička)

config/
config.php – konfigurace databáze a uploadu

public/
index.php – vstupní bod aplikace
assets/
css/ – styly
js/ – skripty
uploads/ – nahrané PDF soubory

sql/
databázový skript

README.txt


UŽIVATELSKÉ ROLE

- Návštěvník – přístup k veřejným částem
- Autor – nahrávání článků
- Recenzent – hodnocení článků
- Admin – správa uživatelů a obsahu
- Superadmin – plná kontrola nad systémem


INSTALACE

1. Naklonuj repozitář z GitHubu.
2. Importuj databázi ze složky sql/.
3. Uprav přístupové údaje v souboru config/config.php.
4. Nastav práva zápisu pro adresář public/assets/uploads/.
5. Spusť aplikaci na lokálním serveru (XAMPP, Laragon apod.).


BEZPEČNOST

- Upload povoluje pouze PDF soubory
- Maximální velikost souboru je nastavitelná v konfiguraci
- Databázové dotazy jsou realizovány pomocí PDO


AUTOR

Jméno: David Kořínek
Email: dkorinek@students.zcu.cz
Předmět: WEB
Rok: 2025


LICENCE

Projekt je určen výhradně ke studijním účelům.
