/**
 * Composants pour les formulaires
 *
 * Jquery
 *
 * Ajout de fonctionnalités et composants esthétique dans les formulaires :
 *          -Infobulles dynamiques
 *          -Liste déroulante avec recherche   
 *  
 * @category  PPE
 * @package   GSB
 * @author    TRUONG Jacky
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();                                     //Infosbulles
    $('.search-list').select2();
    $('.tablesorter').click(function(){                                           //Fonction permettant de cocher toutes les checkbox visibles
        var isChecked = $(this).prop('checked');
        // make sure we only target visible rows; but not in nested tables
        $('table').children('tbody').children('tr:visible').prop('checked', isChecked);
    });
});



/** toutCocher($nomInput)
 * 
 * Permet la sélection/déselection de toutes les checkbox du tableau de suivi de fiche
 * La sélection/déselection peut s'executer via les checkbox situées dans le header et dans le footer du tableau
 * 
 * @param {string} $nomInput Récupération de l'input qui a été cliqué
 * 
 */

function toutCocher(nomInput){

// Récupération du vecteur visiteur[]
var v = document.getElementsByName('visiteurs[]');  

// Si la sélection s'est faite via la checkbox 'tout_cocher', 'tout_cocher_foot' prend la valeur de 'tout_cocher'
// Il se produit la situation inverse si l'utilisateur coche 'tout_cocher_foot'
    if(nomInput === 'tout_cocher'){
        document.getElementById('tout_cocher_foot').checked = document.getElementById('tout_cocher').checked;
    }
    else{
        document.getElementById('tout_cocher').checked = document.getElementById('tout_cocher_foot').checked;
    }
    
//Le script vérifie si une sélection générale a été lancée. Inutile de vérifier la sélection des deux input
//puisque l'un active l'autre au préalable
    if(document.getElementById('tout_cocher').checked === true){                //Si une sélection générale a été lancée
        var nbLignes = document.getElementById('lstNbLignes').value;            //On récupère le nombre de lignes qui aparaissent dans le tableau (lstNbLignes dans le footer)
        if(nbLignes === "all"){                                                 //Si lstNbLignes retourne "all", TOUTES les lignes sont cochées
        nbLignes = v.length;
        }
        
        for(i=0; i < nbLignes; i++){

            if(document.getElementById('visiteur'+i).parentNode.parentNode.className !== 'odd filtered' &&
               document.getElementById('visiteur'+i).parentNode.parentNode.className !== 'even filtered'){  //Controle si la ligne est visible
                document.getElementById('visiteur'+i).checked = true;                                       //Les lignes numérotées sont cochées en fonction de lstNbLignes     
            }
            else                                                                //Si la ligne n'est pas visible, nbLigne est incrémentée de 1
            {
                nbLignes++;
            }        
        }
    }else{                                                                      //Si une désélection générale a été lancée
        for(i=0; i < v.length; i++){
            document.getElementById("visiteur"+i).checked = false;              //TOUTES les lignes sont décochées
        }    
    }
    

}

    
    


