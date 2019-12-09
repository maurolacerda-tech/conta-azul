<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conta Azul</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <?php
    require_once "contaazulModulo.php";
    ?>
</head>
<body class="bg-light">    

    <main class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white-50 rounded shadow-sm">
            <img class="mr-3" src="https://cleverweb.com.br/app2019/includes/images/logo.png" alt="Clever Web" height="48">
            <div class="lh-100">
                <h6 class="mb-0 text-dark lh-100">Integração Teste Conta Azul</h6>
                <small class="text-dark">Hearttis</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Link de conexão</h6>
            <div class="media text-muted pt-3">                
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Auth</strong>
                    <?php
                    $contaazulModulo = new contaazulModulo;
                        echo '<a href="'.$contaazulModulo->getUrlAuth().'"> <img src="https://developers.contaazul.com/images/logo-contaazul.svg" alt="" height="20"> Clique aqui.  </a>';
                    unset($contaazulModulo);
                    ?>
                </p>
            </div>
        </div>
    </main>



</body>
</html>