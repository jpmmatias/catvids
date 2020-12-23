<?php 
class constants 
{
    public static $firstNameCharacters = "Nome tem que ter mais de 1 caracter e menos de 25 caracteres";
    public static $lastNameCharacters = "Sobrenome tem que ter mais de 1 caracter e menos de 25 caracteres";
    public static $usernameCharacters = "Username tem que ter mais de 4 caracteres e menos de 25 caracteres";
    public static $usernameAlreadyExists = "Username já esta em uso, por favor tente outro";
    public static $emailAlreadyExists = "Já existe uma conta com este email, por favor tente novamente";
    public static $emailNotValid = "Por favor insira um endereço valido de email";
    public static $passwordDiferent = "O campo de senha e de confirmação de senha estão diferetens, por favor tente novamente";
    public static $passwordToShort = "A senha precia ter no minimo 5 caracteres, por favor tente novamente";
    public static $passwordTooWeak = "A senha precisa possuir letra, numero e caracteres especiais (como por ex: @, #, $, etc..)";
    public static $loginFail = "O seu username ou senha estava incorreto, tente novamente";
}

?>