# Guide des Programmes TV

Un projet PHP simple pour afficher les programmes TV en temps réel et de la soirée, à partir des données XMLTV.

---

## **Description**

Ce projet permet d'afficher les programmes TV **en ce moment** et **de ce soir** pour une sélection de chaînes françaises, en utilisant les données fournies par [XMLTV France](https://xmltvfr.fr/).

### **Fonctionnalités**
- Récupération automatique des données XMLTV.
- Affichage des programmes **en cours** et **de la soirée** (20h50 à 23h59).
- Affichage des logos des chaînes.
- Descriptions des programmes (si disponibles).
- Mise en évidence des programmes longs (plus d'1 heure).
- Interface responsive pour une consultation sur mobile et desktop.

---
 
## **Prérequis**

- Un serveur web (Apache, Nginx, etc.) avec **PHP 7.4 ou supérieur**.
- L'extension PHP **SimpleXML** activée.
- L'extension PHP **Zip** activée.
- Un accès internet pour télécharger les données XMLTV.

---

## **Installation**

1. **Cloner ou télécharger** ce projet sur votre serveur web.
2. **Créer le dossier `xmltv/`** à la racine du projet et donner les permissions nécessaires :
   ```bash
   mkdir xmltv
   chmod 755 xmltv

## **Automatisation**
Pour une mise à jour automatique des données, configurez un cron job sur votre serveur pour exécuter recupxmltv.php régulièrement (ex. : une fois par jour) :

0 20 * * * /usr/bin/php /chemin/vers/recupxmltv.php

## **Structure du projet**
  ```
  ├── recupxmltv.php      # Script de récupération et extraction du fichier XMLTV
  ├── tv-now.php          # Page affichant les programmes en cours
  ├── tv-night.php        # Page affichant les programmes de ce soir
  ├── xmltv/              # Dossier de stockage des fichiers XML et logos
  │   └── xmltv_fr.xml    # Fichier XML contenant les données des programmes
  └── README.md           # Ce fichier
