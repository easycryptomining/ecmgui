--
-- File generated with SQLiteStudio v3.1.1 on sam. juin 23 17:45:43 2018
--
-- Text encoding used: System
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: plugins
CREATE TABLE plugins (id INTEGER PRIMARY KEY AUTOINCREMENT, crypto BOOLEAN NOT NULL DEFAULT (1) CHECK (crypto IN (0, 1)), arlo BOOLEAN NOT NULL CHECK (arlo IN (0, 1)) DEFAULT (0), wemo BOOLEAN NOT NULL CHECK (wemo IN (0, 1)) DEFAULT (0), hue BOOLEAN NOT NULL CHECK (hue IN (0, 1)) DEFAULT (0));
INSERT INTO plugins (id, crypto, arlo, wemo, hue) VALUES (1, 1, 0, 0, 0);

-- Table: settings
CREATE TABLE settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, arlousername VARCHAR, arlopassword, refresh INTEGER DEFAULT (60), powercost DECIMAL DEFAULT (0.14));
INSERT INTO settings (id, arlousername, arlopassword, refresh, powercost) VALUES (1, '', NULL, 60, 0.14);

-- Table: wallets
CREATE TABLE wallets (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, type VARCHAR, number TEXT, balance DECIMAL NOT NULL DEFAULT (0));

-- Table: wemos
CREATE TABLE wemos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR NOT NULL, type VARCHAR);

-- Table: workers
CREATE TABLE workers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR, ip VARCHAR, pool VARCHAR, insight INTEGER, software VARCHAR, softwareport INTEGER, amp DECIMAL, walletid INTEGER NOT NULL, sshuser VARCHAR, sshpassword VARCHAR, sshport INTEGER);

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
