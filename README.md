Jednostavan E-commerce (PHP + MySQL + Tailwind)

Kratki demo e-commerce sistema sa proizvodima, kategorijama, korisnicima i narudžbama.

Tehnologije:

- PHP (PDO)
- MySQL
- Tailwind CSS (Play CDN)

Brzi koraci:

1. Importujte bazu podataka: otvorite `db.sql` u vašem MySQL klijentu ili pokrenite:

```sql
CREATE DATABASE ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce;
-- zatim importujte sadržaj db.sql
```

2. Konfigurišite konekciju u `src/db.php` ako koristite druge kredencijale.

3. Pokrenite lokalni PHP server iz root projekta:

```powershell
php -S localhost:8000 -t public
```

4. Otvorite `http://localhost:8000`.

Admin:

- Postoji inicijalni admin korisnik: email `admin@shop.test`, password `admin123` (promenite odmah).

Napomena:

- Koristimo Tailwind Play CDN radi brzog primera (nije za produkciju). Za produkciju buildujte Tailwind lokalno.
