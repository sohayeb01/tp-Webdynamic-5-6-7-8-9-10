function afficherConsole(elementId, message) {
    const consoleElement = document.getElementById(elementId);
    consoleElement.innerHTML += message + '<br>';
    consoleElement.classList.add('show');
    console.log(message);
}

function clearConsole(elementId) {
    const consoleElement = document.getElementById(elementId);
    consoleElement.innerHTML = '';
    consoleElement.classList.remove('show');
}

// EXERCICE 1: Opérations arithmétiques
function exercice1() {
    clearConsole('console1');
    
    // Demander deux nombres à l'utilisateur
    let nombre1 = parseFloat(prompt("Entrez le premier nombre :"));
    let nombre2 = parseFloat(prompt("Entrez le deuxième nombre :"));
    
    // Vérifier si les entrées sont valides
    if (isNaN(nombre1) || isNaN(nombre2)) {
        afficherConsole('console1', "Erreur : Veuillez entrer des nombres valides.");
        return;
    }
    
    // Calculer les opérations
    let somme = nombre1 + nombre2;
    let difference = nombre1 - nombre2;
    let produit = nombre1 * nombre2;
    let quotient = nombre2 !== 0 ? nombre1 / nombre2 : "Division par zéro impossible";
    
    // Afficher les résultats
    afficherConsole('console1', "=== RÉSULTATS DES OPÉRATIONS ===");
    afficherConsole('console1', `Nombre 1 : ${nombre1}`);
    afficherConsole('console1', `Nombre 2 : ${nombre2}`);
    afficherConsole('console1', `Somme : ${nombre1} + ${nombre2} = ${somme}`);
    afficherConsole('console1', `Différence : ${nombre1} - ${nombre2} = ${difference}`);
    afficherConsole('console1', `Produit : ${nombre1} × ${nombre2} = ${produit}`);
    afficherConsole('console1', `Quotient : ${nombre1} ÷ ${nombre2} = ${quotient}`);
}

// EXERCICE 2: Jeu de devinette
function exercice2() {
    clearConsole('console2');
    
    // Générer un nombre aléatoire entre 1 et 10
    let nombreSecret = Math.floor(Math.random() * 10) + 1;
    let tentatives = 0;
    let devine = false;
    
    afficherConsole('console2', "=== JEU DE DEVINETTE ===");
    afficherConsole('console2', "J'ai choisi un nombre entre 1 et 10. À vous de le deviner !");
    
    while (!devine) {
        let proposition = parseInt(prompt("Entrez votre proposition (entre 1 et 10) :"));
        tentatives++;
        
        // Vérifier si l'entrée est valide
        if (isNaN(proposition) || proposition < 1 || proposition > 10) {
            afficherConsole('console2', "Veuillez entrer un nombre entre 1 et 10.");
            tentatives--; // Ne pas compter cette tentative
            continue;
        }
        
        afficherConsole('console2', `Tentative ${tentatives} : ${proposition}`);
        
        if (proposition === nombreSecret) {
            devine = true;
            afficherConsole('console2', "🎉 Félicitations ! Vous avez trouvé !");
            afficherConsole('console2', `Le nombre était bien ${nombreSecret}.`);
            afficherConsole('console2', `Votre score : ${tentatives} tentative(s)`);
        } else if (proposition < nombreSecret) {
            afficherConsole('console2', "Le nombre à deviner est plus GRAND que votre proposition.");
        } else {
            afficherConsole('console2', "Le nombre à deviner est plus PETIT que votre proposition.");
        }
    }
}

// EXERCICE 3: Quiz interactif
// Tableau des questions et réponses
const QUESTIONS = [
    ["Quelle est la capitale de la France ?", "Paris"],
    ["Combien font 2 + 2 ?", "4"],
    ["Quel est le langage de programmation utilisé dans ce TP ?", "JavaScript"],
    ["Quelle est la couleur du ciel par temps clair ?", "Bleu"],
    ["Combien y a-t-il de continents sur Terre ?", "7"]
];

function lancerQuiz() {
    clearConsole('console3');
    
    let score = 0;
    let totalQuestions = QUESTIONS.length;
    
    afficherConsole('console3', "=== QUIZ INTERACTIF ===");
    afficherConsole('console3', `Vous allez répondre à ${totalQuestions} questions.`);
    afficherConsole('console3', "");
    
    // Parcourir chaque question
    for (let i = 0; i < QUESTIONS.length; i++) {
        let question = QUESTIONS[i][0];
        let reponseCorrecte = QUESTIONS[i][1].toLowerCase().trim();
        
        // Poser la question à l'utilisateur
        let reponseUtilisateur = prompt(`Question ${i + 1}/${totalQuestions}: ${question}`);
        
        // Vérifier si l'utilisateur a annulé
        if (reponseUtilisateur === null) {
            afficherConsole('console3', "Quiz annulé par l'utilisateur.");
            return;
        }
        
        // Normaliser la réponse de l'utilisateur
        reponseUtilisateur = reponseUtilisateur.toLowerCase().trim();
        
        afficherConsole('console3', `Q${i + 1}: ${question}`);
        afficherConsole('console3', `Votre réponse: ${reponseUtilisateur}`);
        
        // Comparer les réponses
        if (reponseUtilisateur === reponseCorrecte) {
            afficherConsole('console3', "✅ Réponse juste");
            score++;
        } else {
            afficherConsole('console3', `❌ Réponse fausse (Réponse correcte: ${QUESTIONS[i][1]})`);
        }
        afficherConsole('console3', "");
    }
    
    // Afficher le résultat final
    afficherConsole('console3', "=== RÉSULTATS DU QUIZ ===");
    afficherConsole('console3', `Vous avez répondu correctement à ${score} question(s) sur ${totalQuestions}.`);
    
    // Ajouter un commentaire selon le score
    let pourcentage = (score / totalQuestions) * 100;
    if (pourcentage === 100) {
        afficherConsole('console3', "🎉 Parfait ! Vous avez tout juste !");
    } else if (pourcentage >= 80) {
        afficherConsole('console3', "👏 Très bien joué !");
    } else if (pourcentage >= 60) {
        afficherConsole('console3', "👍 Pas mal !");
    } else {
        afficherConsole('console3', "💪 Il faut réviser un peu plus !");
    }
}