<?php
class Upload { // CLASS POUR LES FICHIER UPLOAD DANS LA BDD/ serveur
    private $extensions;
    private $chemin;
    private $taille;

    /**
     * @param array $extensions Extensions autorisées (ex: ['jpg', 'png'])
     * @param string $chemin Dossier de destination
     * @param int $taille Taille maximale en octets (ex: 2 Mo = 2097152)
     */
    public function __construct($extensions, $chemin, $taille) {
        $this->extensions = $extensions;
        $this->chemin = $chemin;
        $this->taille = $taille;
    }

    /**
     * Enregistre le fichier envoyé via un champ $_FILES
     * @param string $data Le nom du champ input dans le formulaire
     * @return array ['nom' => string|null, 'erreur' => string|null]
     */
    public function enregistrer($data) {
        $fichier = [
            'nom' => null,
            'erreur' => null
        ];

        if (isset($_FILES[$data]) && !empty($_FILES[$data]['name'])) {
            $extension = strtolower(pathinfo($_FILES[$data]['name'], PATHINFO_EXTENSION));

            // Vérifie l'extension
            if (!in_array($extension, $this->extensions)) {
                $msg = 'Veuillez sélectionner un fichier de type : ' . implode(', ', $this->extensions);
                $fichier['erreur'] = $msg;
                return $fichier;
            }

            // Vérifie la taille du fichier
            if (file_exists($_FILES[$data]['tmp_name']) && filesize($_FILES[$data]['tmp_name']) > $this->taille) {
                $msg = 'Votre fichier doit faire moins de ' . ($this->taille / 1024) . ' Ko !';
                $fichier['erreur'] = $msg;
                return $fichier;
            }

            // Nettoyage du nom de fichier
            $nomOriginal = basename($_FILES[$data]['name']);
            $nomNettoye = strtr(
                $nomOriginal,
                'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
                'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
            );
            $nomNettoye = preg_replace('/([^.a-z0-9]+)/i', '_', $nomNettoye);

            // Déplacement du fichier
            $destination = rtrim($this->chemin, '/') . '/' . $nomNettoye;
            if (move_uploaded_file($_FILES[$data]['tmp_name'], $destination)) {
                $fichier['nom'] = $nomNettoye;
            } else {
                $fichier['erreur'] = 'Erreur lors de la copie du fichier.';
            }
        }

        return $fichier;
    }
}
?>
