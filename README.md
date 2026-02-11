# Progetto E-commerce (stile Amazon) - PHP + MySQL (XAMPP)

Questa implementazione copre i requisiti richiesti:
- ruoli `amministratore` e `utente`;
- registrazione/login con ruoli;
- dashboard separate;
- CRUD prodotti lato amministratore;
- carrello con sessioni + Ajax;
- checkout con metodo di pagamento;
- storico ordini utente;
- layout responsive.

## 1) Setup su XAMPP
1. Copia la cartella progetto dentro `htdocs`.
2. Avvia Apache e MySQL dal pannello XAMPP.
3. Apri phpMyAdmin e importa `schema.sql`.
4. Apri `config.php` e configura host/user/password DB se necessario.
5. `APP_BASE_URL` viene rilevato automaticamente (es. `/doveri` su XAMPP e vuoto in sviluppo locale). In alternativa puoi forzarlo con variabile ambiente `APP_BASE_URL`.
6. Vai su `http://localhost/doveri` (o l'URL locale che stai usando).

## 2) File principali
- `config.php`: connessione DB, sessione, helper sicurezza, ruolo e autenticazione.
- `index.php`: home page.
- `register.php`, `login.php`: autenticazione.
- `dashboard_admin.php`: accesso area amministratore.
- `dashboard_user.php`: accesso area utente.
- `admin/products.php`: CRUD prodotti.
- `admin/users.php`: lista utenti.
- `products.php`: ricerca + aggiunta al carrello.
- `cart.php`: riepilogo carrello + checkout.
- `place_order.php`: creazione ordine.
- `ajax/cart_add.php`, `ajax/cart_remove.php`: endpoint Ajax.

## 3) Sicurezza e privacy password
- Password salvate con `password_hash()` (bcrypt) nel campo `password_hash`.
- Verifica con `password_verify()` durante il login.
- Query con prepared statements (PDO) per mitigare SQL injection.
- Escape HTML con helper `e()` per ridurre XSS.
- Token CSRF su form critici.
- Separazione autorizzazioni con `require_role('amministratore'|'utente')`.

## 4) Account demo
- Admin: `admin@demo.local`
- Password: `Admin123!`

## 5) Nota
Progetto didattico: si può estendere con immagini prodotto, paginazione, tracking spedizione e logging avanzato.
