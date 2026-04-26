<?php
class User{
    Protected $id;
    Protected $nom;
    Protected $prenom;
    Protected $tel;
    Protected $adresse;
    Protected $email;
    Protected $mot_de_passe;
    Protected $role;

    Public function __construct($id,$nom,$prenom,$tel,$adresse,$email,$mot_de_passe,$role='client'){
        $this->id=$id;
        $this->nom=$nom;
        $this->prenom=$prenom;
        $this->tel=$tel;
        $this->adresse=$adresse;
        $this->email=$email;
        $this->mot_de_passe=$mot_de_passe;
        $this->role=$role;
    }
    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of tel
     */ 
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set the value of tel
     *
     * @return  self
     */ 
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get the value of adresse
     */ 
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set the value of adresse
     *
     * @return  self
     */ 
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of mot_de_passe
     */ 
    public function getMot_de_passe()
    {
        return $this->mot_de_passe;
    }

    /**
     * Set the value of mot_de_passe
     *
     * @return  self
     */ 
    public function setMot_de_passe($mot_de_passe)
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
}
?>

