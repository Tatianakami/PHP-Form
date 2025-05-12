<?php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Redireciona se não for POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

// Salva dados do formulário para caso de erro
$_SESSION['old'] = $_POST;

// Validações
$errors = [];

// Campos obrigatórios
$required = ['nome', 'email', 'senha', 'confirmar_senha', 'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $errors[$field] = "Este campo é obrigatório";
    }
}

// Validação de e-mail
if (!empty($_POST['email'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail inválido";
    } else {
        // Verifica se e-mail já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = "Este e-mail já está cadastrado";
        }
    }
}

// Validação de senha
if (!empty($_POST['senha'])) {
    if (strlen($_POST['senha']) < 8) {
        $errors['senha'] = "A senha deve ter no mínimo 8 caracteres";
    }
}

// Confirmação de senha
if (!empty($_POST['senha']) && !empty($_POST['confirmar_senha'])) {
    if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        $errors['confirmar_senha'] = "As senhas não coincidem";
    }
}

// Validação de CEP
if (!empty($_POST['cep'])) {
    $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);
    if (strlen($cep) != 8) {
        $errors['cep'] = "CEP inválido";
    }
}

// Validação de idade
if (!empty($_POST['idade'])) {
    if ($_POST['idade'] < 0 || $_POST['idade'] > 120) {
        $errors['idade'] = "Idade inválida";
    }
}

// Validação 
if (!empty($_POST['orientacao_sexual']) && !in_array($_POST['orientacao_sexual'], [
    'Heterossexual', 'Homossexual', 'Bissexual', 'Pansexual', 'Assexual', 'Outro'
])) {
    $errors['orientacao_sexual'] = "Selecione uma opção válida";
}

if (!empty($_POST['identidade_genero']) && !in_array($_POST['identidade_genero'], [
    'Cisgênero', 'Transgênero', 'Não binário', 'Gênero fluido', 'Outro'
])) {
    $errors['identidade_genero'] = "Selecione uma opção válida";
}

// Se houver erros, redireciona de volta ao formulário
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    redirect('index.php');
}

// Tratamento da diversidade (checkbox múltiplo)
if (isset($_POST['diversidade'])) {
    $diversidade_array = $_POST['diversidade'];
    
    // Trata o "Outro" se existir
    if (in_array('Outro', $diversidade_array) && !empty($_POST['diversidade_outro'])) {
        // Substitui o valor "Outro" pelo texto personalizado
        $key = array_search('Outro', $diversidade_array);
        $diversidade_array[$key] = 'Outro: ' . htmlspecialchars($_POST['diversidade_outro']);
    }
    
    $diversidade_str = implode(', ', $diversidade_array);
} else {
    $diversidade_str = null;
}

// Prepara dados para inserção
$dados = [
    ':nome' => sanitize($_POST['nome']),
    ':sobrenome' => !empty($_POST['sobrenome']) ? sanitize($_POST['sobrenome']) : null,
    ':email' => $_POST['email'],
    ':telefone' => !empty($_POST['telefone']) ? sanitize($_POST['telefone']) : null,
    ':data_nascimento' => !empty($_POST['data']) ? $_POST['data'] : null,
    ':idade' => !empty($_POST['idade']) ? (int)$_POST['idade'] : null,
    ':cep' => preg_replace('/[^0-9]/', '', $_POST['cep']),
    ':endereco' => sanitize($_POST['endereco']),
    ':numero' => sanitize($_POST['numero']),
    ':complemento' => !empty($_POST['complemento']) ? sanitize($_POST['complemento']) : null,
    ':bairro' => sanitize($_POST['bairro']),
    ':cidade' => sanitize($_POST['cidade']),
    ':estado' => $_POST['estado'],
    ':orientacao_sexual' => $_POST['orientacao_sexual'] ?? null,
    ':identidade_genero' => $_POST['identidade_genero'] ?? null,
    ':outros_conceitos' => !empty($_POST['outros_conceitos']) ? 
                          implode(', ', $_POST['outros_conceitos']) : null,
    ':diversidade' => $diversidade_str,
    ':senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT)
];

try {
    $sql = "INSERT INTO usuarios (
                nome, sobrenome, email, telefone, data_nascimento,
                idade, cep, endereco, numero, complemento, bairro,
                cidade, estado, orientacao_sexual, identidade_genero,
                outros_conceitos, diversidade, senha
            ) VALUES (
                :nome, :sobrenome, :email, :telefone, :data_nascimento,
                :idade, :cep, :endereco, :numero, :complemento, :bairro,
                :cidade, :estado, :orientacao_sexual, :identidade_genero,
                :outros_conceitos, :diversidade, :senha
            )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($dados);
    
    // Limpa dados antigos após sucesso
    unset($_SESSION['old']);
    set_message("Cadastro realizado com sucesso!", 'success');
} catch (PDOException $e) {
    set_message("Erro no sistema: " . $e->getMessage(), 'error');
}

redirect('index.php');