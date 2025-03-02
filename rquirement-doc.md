# Anforderungsdokument: "Roomfull" Co-Working-Space Buchungssystem

## 1. Projektübersicht

**Projektname:**
Roomfull

**Beschreibung:**
Ein webbasiertes Buchungssystem für einen Co-Working-Space, das sowohl Kunden als auch Administratoren eine benutzerfreundliche Oberfläche zur Verwaltung von Raumbuchungen bietet.

**Hauptziele:**

-   Effiziente Verwaltung verschiedener Raumtypen und deren Buchungen
-   Kundenfreundliche Buchungsplattform
-   Admin-Dashboard für die Verwaltung von Räumen, Buchungen und Benutzern
-   **_Klare Trennung zwischen Admin- und Kundenfunktionalitäten_**

## 2. Funktionale Anforderungen

### 2.1 Raumverwaltung

**Verwaltung verschiedener Raumtypen:**

-   Meetingräume (Kapazität: 16 bzw. 8 Personen)
-   Office-Räume (Kapazität: je 10 Personen)
-   Booths (Kapazität: je 3 Personen)
-   "Open World" mit Einzelplätzen
-   Informationen zu jedem Raum:
-   Name und Beschreibung
-   Kapazität (Personenanzahl)
-   Preis pro Stunde
-   Mindestbuchungsdauer (abhängig vom Raumtyp)
-   Aktivierungsstatus (verfügbar/nicht verfügbar)

### 2.2 Buchungssystem

-   Benutzer können verfügbare Räume einsehen
-   Kalender mit Verfügbarkeitsanzeige
-   Buchungsprozess:
    -   Benutzer wählt Raum
    -   Wählt Datum und Zeitfenster
    -   Gibt persönliche Informationen ein (für Neukunden) oder loggt sich ein
    -   Erhält Bestätigung der Buchung
-   Verschiedene Mindestmietdauern je nach Raumtyp
-   Preisberechnung basierend auf Dauer und Raumtyp
-   Visuelles Feedback während des Buchungsprozesses mit Status-Anzeige

### 2.3 Benutzerverwaltung

-   Zwei Benutzerrollen:
    -   Admin (Vollzugriff auf alle Funktionen)
    -   Kunde (kann nur Räume buchen und eigene Buchungen verwalten)
-   Benutzerregistrierung und Authentifizierung
-   Profilverwaltung
-   Rollenbasierte Navigation und Zugriffsrechte

### 2.4 Admin-Dashboard

-   Übersicht aller Buchungen
-   Raum- und Sitzplatzverwaltung
-   Benutzerverwaltung
-   Statistiken und Berichte
-   Zugangsbeschränkung durch Middleware und Autorisierung

### 2.5 Einzelplatzverwaltung im "Open World"-Bereich

-   Visuelle Darstellung der Sitzplätze
-   Individuelle Buchung von Einzelplätzen
-   Gruppierung von Sitzplätzen möglich

## 3. Datenbankstruktur

### 3.1 Geplante Modelle

#### Room

-   id: Primärschlüssel
-   name: Name des Raums
-   type: Art des Raums (Enum: meeting, office, booth, open_world)
-   capacity: Kapazität (Anzahl der Personen)
-   description: Beschreibung des Raums
-   min_duration: Mindestbuchungsdauer in Minuten
-   price_per_hour: Preis pro Stunde
-   price_per_day: Preis pro Tag (optional)
-   price_per_week: Preis pro Woche (optional)
-   discount_percentage: Rabatt in Prozent (optional)
-   is_active: Ob der Raum buchbar ist
-   timestamps: created_at, updated_at

#### Booking

-   id: Primärschlüssel
-   room_id: Fremdschlüssel zum Raum
-   user_id: Fremdschlüssel zum Nutzer
-   start_time: Startzeit der Buchung
-   end_time: Endzeit der Buchung
-   status: Status der Buchung (Enum: pending, confirmed, cancelled)
-   price: Gesamtpreis
-   notes: Anmerkungen zur Buchung
-   timestamps: created_at, updated_at

#### User

-   id: Primärschlüssel
-   name: Name des Nutzers
-   email: E-Mail-Adresse
-   password: Passwort (gehashed)
-   role: Rolle (Enum: admin, customer)
-   timestamps: created_at, updated_at

#### Seat

-   id: Primärschlüssel
-   room_id: Fremdschlüssel zum Open World-Raum
-   name: Name/Nummer des Platzes
-   position_x, position_y: Position im Raumplan
-   is_active: Ob der Platz buchbar ist
-   timestamps: created_at, updated_at

### 3.2 Beziehungen zwischen Modellen

-   Room hat viele Bookings (one-to-many)
-   Room vom Typ "open_world" hat viele Seats (one-to-many)
-   User hat viele Bookings (one-to-many)

## 4. Technischer Stack

### 4.1 Backend

-   Framework: Laravel 12
-   PHP-Version: 8.2+
-   Datenbank: MySQL 8.0
-   Entwicklungsumgebung: Docker

### 4.2 Docker-Setup

Container:

-   app (PHP 8.2 mit Laravel)
-   nginx (Webserver)
-   db (MySQL 8.0)

Konfigurationsdateien:

-   docker-compose.yml
-   Dockerfile
-   nginx/conf.d/app.conf

### 4.3 Konfigurationsdetails

-   DB_CONNECTION=mysql
-   DB_HOST=db (Docker-Container-Name)
-   DB_PORT=3306
-   DB_DATABASE=roomfull
-   DB_USERNAME=roomfull_user
-   DB_PASSWORD=password

## 5. Aktueller Implementierungsstand

### 5.1 Abgeschlossene Schritte

-   Projekt-Setup mit Laravel 12
-   Docker-Entwicklungsumgebung konfiguriert und getestet
-   Grundlegende Datenbank-Konfiguration
-   Erste Laravel-Anwendung läuft erfolgreich im Browser
-   Room-Modell und Controller implementiert
-   Booking-Modell und Controller für Kundenseite implementiert
-   Verfügbarkeitsprüfung und Preisberechnung funktionieren

### 5.2 Docker-Befehle

Container starten:
`docker-compose up -d`

Composer-Befehle ausführen:
`docker-compose exec app composer [command]`

Artisan-Befehle ausführen:
`docker-compose exec app php artisan [command]`

Container stoppen:
`docker-compose down`

## 6. Nächste Schritte

### 6.1 Rollenbasiertes Zugriffssystem implementieren

1. User-Modell erweitern

-   Migration erstellen, um das 'role'-Feld hinzuzufügen, falls nicht vorhanden
-   Enum-Werte für Benutzerrollen definieren (admin, customer)
-   Zugriffshelfer-Methoden zum User-Modell hinzufügen (isAdmin(), isCustomer())

2. Middleware für Admin-Zugriff erstellen

-   AdminMiddleware-Klasse implementieren
-   Middleware in Kernel.php registrieren
-   Middleware auf Admin-Routen anwenden

3. Routen-Gruppen konfigurieren

-   Öffentliche Routen definieren
-   Kundenrouten mit 'auth'-Middleware schützen
-   Admin-Routen mit 'auth' und 'admin'-Middleware schützen
-   Route-Prefixing für Admin-Bereich (/admin/...)

4. Navigation und UI anpassen

-   Rollenbasierte Navigationsleiste erstellen
-   Admin-spezifische Menüpunkte nur für Admins anzeigen
-   Kunden-spezifische Menüpunkte nur für Kunden anzeigen

### 6.2 Admin-Dashboard entwickeln

1. Admin-Controller erstellen

-   Dashboard-Methode für Übersicht
-   Statistik-Methode für Auswertungen
-   Verwaltungsmethoden für Räume und Buchungen

2. Admin-Buchungsverwaltung

-   Alle Buchungen anzeigen (mit Filterfunktionen)
-   Status-Änderung von Buchungen ermöglichen
    Buchungsdetails einsehen

3. Admin-Raumverwaltung

-   Räume erstellen, bearbeiten und deaktivieren
-   Raumbelegung einsehen
-   Preise und Konditionen verwalten

4. Admin-Ansichten (Blade-Templates)

-   Dashboard-Layout erstellen
-   Buchungsverwaltungsansicht
-   Raumverwaltungsansicht
-   Statistikansicht

### 6.3 Verbessertes Buchungssystem

1. Buchungsbestätigung mit Animation

-   Status-Übergang von "pending" zu "confirmed" mit visueller Animation
-   JavaScript für verzögerten API-Aufruf implementieren
-   Verarbeitungsanzeige für bessere UX

2. E-Mail-Benachrichtigungen

-   E-Mail-Templates erstellen
-   Benachrichtigungsklassen implementieren
-   Automatische E-Mails bei Buchungsänderungen

### 6.4 Fortgeschrittene Features

1. Open World-Bereich

-   Seat-Modell implementieren
-   Visuelle Darstellung der Sitzplätze
-   Interaktive Buchung von Einzelplätzen

2. Statistik und Auswertungen

-   Dashboard mit Kennzahlen
-   Umsatzstatistiken
-   Auslastungsberichte

### 6.5 Zusätzliche Verbesserungen

1. API für mobile Anwendungen

-   RESTful API-Endpunkte erstellen
-   API-Authentifizierung mit Sanctum/Passport
-   Dokumentation der API-Endpunkte

2. Testautomatisierung

-   Unit-Tests für wichtige Funktionen
-   Feature-Tests für Benutzerinteraktionen
-   CI/CD-Pipeline einrichten
