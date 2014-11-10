<?php
/**
* French Language Strings for phpVMS
*
* @author SilentT <http://silentt.fr>
*
* You can use this file to create your own translations
* Format is
*
* key=>string
*
* Key must be lower case, no underscores. These are mainly for the
* admin panel.
*/

/**
* French language 
* Verification problem accented character ( UTF-8 )
*/

$trans = array(

/* Define some core language stuff */
'invalid.php.version'	=> 'Vous n'utilisez pas PHP version 5.0 +',
'database.connection.failed'	=> 'La connexion à la base de données à échouée',
'error'	=> 'Une erreur s'est produite (%s)', /* %s est la chaîne d\'erreur */

/*
* Module language replacements
*/

/* Email stuff */
'email.inuse'	=> 'Cette adresse email est déjà utilisé',
'email.register.accepted.subject'	=> 'Votre inscription a été acceptée !',
'email.register.rejected.subject'	=> 'Votre inscription a été refusé !',
'email.pilot.retired.subject'	=> SITE_NAME.': Vous avez été marqué comme inactif',

/* Expenses */



/* Registration Errors */
'password.wrong.length'	=> 'Mot de passe inférieure à 5 caractères',
'password.no.match'	=> 'Les mots de passe ne correspondent pas',
'password.changed'	=> 'Mot de passe a été modifie avec succès',

/* Pilots Info */
'pilot.deleted'	=> 'Pilote supprimé',

/* Awards */
'award.exists'	=> 'Le pilote a déjà cette récompense !',
'award.deleted'	=> 'Récompense supprimé !',

/* Groups */
'group.added'	=> 'Le groupe %s a été ajoutée', /* %s est le nom du groupe */
'group.saved'	=> 'Le groupe %s a été enregistré', /* %s est le nom du groupe */
'group.no.name'	=> 'Vous devez entrer un nom pour le groupe',
'group.pilot.already.in'	=> 'Cet utilisateur est déjà dans ce groupe !',
'group.add.error'	=> 'Il ya eu une erreur en ajoutant cet utilisateur',
'group.user.added'	=> 'L\'utilisateur a été ajouté au groupe !',

/* Pages */
'page.add.title'	=> 'Ajouter une page',
'page.edit.title'	=> 'Modifier la page',
'page.exists'	=> 'Cette page existe déjà !',
'page.create.error'	=> 'Il ya eu une erreur de création du fichier',
'page.edit.error'	=> 'Il ya eu une erreur de modification de la page',
'page.error.delete'	=> 'Il ya eu une erreur de suppression de la page !',
'page.deleted'	=> 'La page a été supprimée',

/* News */
'news.add.title'	=> 'Ajouter News',
'news.edit.title'	=> 'Modifier la News',
'news.updated.success'	=> 'News modifié avec succès !',
'news.updated.error'	=> 'Il ya eu une erreur de modification de la news',
'news.delete.error'	=> 'Il ya eu une erreur de suppression de la news',
'news.item.deleted'	=> 'La News a été supprimée',

/* Settings */
'settings.add.field'	=> 'Ajouter un champ',
'settings.edit.field'	=> 'Modifier le champ',
'pirep.field.add'	=> 'Ajouter un champ PIREP',
'pirep.field.edit'	=> 'Modifier le champ PIREP',

/* PIREPS */
'pireps.view.recent'	=> 'Rapports récents',


/*
* Template language replacements
*/


);
