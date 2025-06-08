````markdown
# 📊 Qard API Integration — Mini Application Symfony

Ce projet est une mini application Symfony développée dans le cadre d’un test technique. Il permet d’intégrer l’API Qard pour afficher et exporter des données d'entreprises synchronisées.

---

## 🚀 Fonctionnalités principales

- 🔐 Authentification à l'API Qard via clé API (stockée en `.env`)
- 📄 Liste des entreprises synchronisées (/companies)
- 🔍 Détail d’une entreprise (/companies/{id}) :
  - Profil
  - Dirigeants
  - Bilans financiers
- 📊 Dashboard (/dashboard) :
  - Nombre total d'entreprises
  - Répartition par statut juridique
  - Graphique du chiffre d'affaires (via Chart.js)
- 📥 Export :
  - PDF des entreprises (Dompdf)
  - Excel des entreprises (PhpSpreadsheet)

---

## ⚙️ Prérequis

- PHP 7.4+
- Composer
- MySQL
- Extensions PHP nécessaires :
  - `ext-gd`
  - `ext-fileinfo`
- Symfony CLI (optionnel mais recommandé)

---

## 🛠️ Installation & Lancement

1. **Cloner le dépôt :**
   ```bash
   git clone <url-du-repo>
   cd qard-api
````

2. **Installer les dépendances :**

   ```bash
   composer install
   ```

3. **Configurer l’environnement :**
   Copier le fichier `.env` :

   ```bash
   cp .env .env.local
   ```

   Modifier `.env.local` et définir la variable :

   ```
   QARD_API_KEY=your_qard_api_key
   ```

4. **Créer la base de données :**

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Lancer le serveur Symfony :**

   ```bash
   symfony server:start
   ```

6. **(Optionnel) Importer les entreprises via API :**

   ```bash
   php bin/console app:import-users
   ```

---

## 🔑 Points clés de lʼimplémentation

* **Structure MVC** avec séparation claire :

  * `Controller/` pour la logique HTTP
  * `Service/QardApiService` pour la communication avec l'API
  * `Entity/` pour les entités Doctrine
  * `Repository/` pour les accès BDD

* **Appels API** : tous les appels passent par un service centralisé `QardApiService`, utilisant `HttpClientInterface`.

* **Affichage avec Twig** : utilisation de layouts et vues spécifiques pour les entreprises et le dashboard.

* **Exports** :

  * PDF généré avec Dompdf à partir d’un template Twig (`pdf.html.twig`)
  * Excel généré dynamiquement avec PhpSpreadsheet

* **Gestion d’erreurs** : les réponses API sont testées, les erreurs sont affichées proprement ou loguées.

---

## 🧪 Tests (Bonus)

> Non inclus pour le moment. Des tests unitaires peuvent être ajoutés dans `/tests/`.

---

## 🐳 Docker (Bonus)

> Optionnel — Peut être ajouté pour conteneuriser le projet.

---

## 📬 Contact

> Développé par **\[Haffsa Senhaji]** dans le cadre d’un test technique pour Qard.

```

---

Souhaites-tu que je l'adapte en français 100% (actuellement un peu bilingue technique), ou tu veux que je génère le `README.md` réel dans un fichier prêt à l’emploi ?
```
