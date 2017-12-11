# APISandwich

## Routes

### Catégories

Accéder à la liste des catégories: (via un get)
>categories

Accéder à une catégorie: (via un get)
>categorie/{id}

Créer une catégorie: (via un post)
>categories

Modifier une catégorie: (via un put)
>categorie/{id}

### Sandwichs

Accéder à la liste des sandwichs: (via un get)
>sandwichs

Accéder à un sandwich: (via un get)
>sandwich/{id}

Paramètres possibles:
>sandwich?t=&img=&p=&s=
t est le type de pain,
img est l'image,
p est la page,
s est le nombre de resultat par page (10 par default)