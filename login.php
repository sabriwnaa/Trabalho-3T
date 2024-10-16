<?php
session_start();

if(!isset($_POST['botao'])){
    header("location: index.php");
    exit();
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$senha = htmlspecialchars($_POST['senha']);

$db = new mysqli("localhost", "root", "", "pokemons_dataset");

$stmt = $db->prepare("SELECT * FROM pessoa WHERE email = ?");
$stmt->bind_param("s", $email);

$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows == 0){
    $_SESSION['erro_login'] = "Nenhuma conta foi encontrada com o endereço de e-mail informado.";
    header("location: index.php");
    exit();
} else {
    $pessoa = $resultado->fetch_assoc();

    if(password_verify($senha, $pessoa['senha'])){
        $_SESSION['id'] = $pessoa['id_pessoa'];
        $_SESSION['email'] = $pessoa['email'];
        
         header("location: restrita.php");
        exit();
    } else {
        $_SESSION['erro_login'] = "A senha informada está incorreta.";
        header("location: index.php");
        exit();
    }
}
?>
