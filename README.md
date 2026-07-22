# appCreateurPersona

Application **Créateur de Personnages** pour le framework `systeme` (WAMP / vieux-site-refactor-wamp).

## Installation

1. Copier le dossier `appCreateurPersona` à la racine du site (à côté de `systeme/`, `motif/`, etc.).
2. Ouvrir :  
   `http://localhost/leSite/index.php?application=CreateurPersona`

Aucune modification du noyau n’est nécessaire (découverte automatique).

## Fonctionnalités

- Liste des personnages
- Création / Modification / Suppression
- Formulaire complet (Identité, Apparence, Mensurations, Personnalité, Background)
- Menus déroulants + multi-sélection « Style de réponse »
- Génération automatique à chaque sauvegarde :
  - `character.json`
  - `memory.json`
  - `rules.md`
- Stockage local dans `appCreateurPersona/data/personnages/{Nom}/`

## Structure

```
appCreateurPersona/
├── CtrCreateurPersona.class.php
├── modele/
│   └── Persona.class.php
├── vue/
│   ├── index.php
│   ├── form.php
│   └── resources/
├── data/
│   ├── options.json
│   └── personnages/
└── README.md
```

## Notes

- Respecte strictement les conventions du template `template-app-systeme-php`.
- Compatible avec les phases 1–7 du refactor (CSRF, MessageFlash, thème, découverte auto).
