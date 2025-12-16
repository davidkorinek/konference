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

src/
models/ – databázové modely

src/
view/ – zobrazovací šablony

src/
partials/ – společné části stránek (hlavička, menu, patička)

config/
config.php – konfigurace databáze a uploadu

public/
index.php – vstupní bod aplikace

public/
assets/
css/ – styly

public/
assets/
js/ – skripty
uploads/ – nahrané PDF soubory

sql/
databázové skripty

README.md


UŽIVATELSKÉ ROLE

- Návštěvník – přístup k veřejným částem
- Autor – nahrávání článků
- Recenzent – hodnocení článků
- Admin – správa uživatelů a obsahu
- Superadmin – plná kontrola nad systémem


INSTALACE

1. Instaluj XAMPP a naklonuj repozitář, který umísti do C:\xampp\htdocs
2. Po spuštění Apache a MySQL v XAMPP, otevřít localhost/phpmyadmin.
3. Přes SQL konzoli spustit napřed scheme.sql a poté seed_data.sql.
4. Uprav přístupové údaje v souboru config/config.php.
5. Otevři localhost/konference/public -> HOTOVO


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
