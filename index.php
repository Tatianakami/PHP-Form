<?php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Limpa mensagens flash e mantém dados antigos se houver erro
$message = $_SESSION['message'] ?? null;
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['message'], $_SESSION['errors']);
if (empty($_POST)) {
    unset($_SESSION['old']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="/cursophp/projeto_php/assets/css/style.css">
    <style>
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert.success { background: #d4edda; color: #155724; }
        .alert.error { background: #f8d7da; color: #721c24; }
        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            display: block;
            margin-top: 5px;
        }
        .error-field {
            border-color: #dc3545 !important;
        }
        #cep {
            width: 150px;
        }
        .loading-cep {
            position: relative;
        }
        .loading-cep::after {
            content: "Buscando...";
            position: absolute;
            right: -90px;
            color: #666;
            font-size: 0.8em;
        }
        .radio-group, .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
        }
        .radio-group label, .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .radio-group input[type="radio"],
        .checkbox-group input[type="checkbox"] {
            margin: 0;
            width: auto;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert <?= $message['type'] ?>">
                <?= $message['text'] ?>
            </div>
        <?php endif; ?>

        <h1>Cadastro de Usuário</h1>
        <form action="/cursophp/projeto_php/cadastro.php" method="post">
            <!-- Dados Pessoais -->
            <fieldset>
                <legend>Dados Pessoais</legend>
                
                <div class="form-group">
                    <label for="nome">Nome*</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?= htmlspecialchars($old['nome'] ?? '') ?>"
                           class="<?= isset($errors['nome']) ? 'error-field' : '' ?>">
                    <?php if (isset($errors['nome'])): ?>
                        <span class="error-message"><?= $errors['nome'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" id="sobrenome" name="sobrenome" 
                           value="<?= htmlspecialchars($old['sobrenome'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           class="<?= isset($errors['email']) ? 'error-field' : '' ?>">
                    <?php if (isset($errors['email'])): ?>
                        <span class="error-message"><?= $errors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" 
                           value="<?= htmlspecialchars($old['telefone'] ?? '') ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="data">Data Nascimento</label>
                        <input type="date" id="data" name="data" 
                               value="<?= htmlspecialchars($old['data'] ?? '') ?>"
                               class="<?= isset($errors['data']) ? 'error-field' : '' ?>">
                        <?php if (isset($errors['data'])): ?>
                            <span class="error-message"><?= $errors['data'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="idade">Idade</label>
                        <input type="number" id="idade" name="idade" min="0" 
                               value="<?= htmlspecialchars($old['idade'] ?? '') ?>"
                               class="<?= isset($errors['idade']) ? 'error-field' : '' ?>">
                        <?php if (isset($errors['idade'])): ?>
                            <span class="error-message"><?= $errors['idade'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>

            <!-- Endereço -->
            <fieldset>
                <legend>Endereço</legend>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cep">CEP*</label>
                        <input type="text" id="cep" name="cep" required
                               pattern="\d{5}-?\d{3}" 
                               title="Digite um CEP válido (00000-000 ou 00000000)"
                               value="<?= htmlspecialchars($old['cep'] ?? '') ?>"
                               class="<?= isset($errors['cep']) ? 'error-field' : '' ?>">
                        <?php if (isset($errors['cep'])): ?>
                            <span class="error-message"><?= $errors['cep'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade*</label>
                        <input type="text" id="cidade" name="cidade" required
                               value="<?= htmlspecialchars($old['cidade'] ?? '') ?>"
                               class="<?= isset($errors['cidade']) ? 'error-field' : '' ?>">
                        <?php if (isset($errors['cidade'])): ?>
                            <span class="error-message"><?= $errors['cidade'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço*</label>
                    <input type="text" id="endereco" name="endereco" required
                           value="<?= htmlspecialchars($old['endereco'] ?? '') ?>"
                           class="<?= isset($errors['endereco']) ? 'error-field' : '' ?>">
                    <?php if (isset($errors['endereco'])): ?>
                        <span class="error-message"><?= $errors['endereco'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="numero">Número*</label>
                        <input type="text" id="numero" name="numero" required
                               value="<?= htmlspecialchars($old['numero'] ?? '') ?>"
                               class="<?= isset($errors['numero']) ? 'error-field' : '' ?>">
                        <?php if (isset($errors['numero'])): ?>
                            <span class="error-message"><?= $errors['numero'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento"
                               value="<?= htmlspecialchars($old['complemento'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro*</label>
                        <input type="text" id="bairro" name="bairro" required
                               value="<?= htmlspecialchars($old['bairro'] ?? '') ?>"
                               class="<?= isset($errors['bairro']) ? 'error-field' : '' ?>">
                        <?php if (isset($errors['bairro'])): ?>
                            <span class="error-message"><?= $errors['bairro'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado*</label>
                        <select id="estado" name="estado" required
                                class="<?= isset($errors['estado']) ? 'error-field' : '' ?>">
                            <option value="">Selecione</option>
                            <?php
                            $estados = [
                                'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal',
                                'ES' => 'Espírito Santo', 'GO' => 'Goiás', 'MA' => 'Maranhão',
                                'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul', 'MG' => 'Minas Gerais',
                                'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná', 'PE' => 'Pernambuco',
                                'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima',
                                'SC' => 'Santa Catarina', 'SP' => 'São Paulo', 'SE' => 'Sergipe',
                                'TO' => 'Tocantins'
                            ];
                            
                            foreach ($estados as $sigla => $nome) {
                                $selected = ($old['estado'] ?? '') === $sigla ? 'selected' : '';
                                echo "<option value=\"$sigla\" $selected>$nome</option>";
                            }
                            ?>
                        </select>
                        <?php if (isset($errors['estado'])): ?>
                            <span class="error-message"><?= $errors['estado'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>

           <!-- Diversidade -->
<div class="colapsavel">
  <div class="colapsavel-titulo" onclick="toggleDiversidade()">
    <span>Diversidade (Opcional)</span>
    <span class="seta">▼</span>
  </div>

  <div class="colapsavel-conteudo" id="diversidade-opcoes" style="display: none;">
    <div class="checkbox-group">
      <label><input type="checkbox" name="diversidade[]" value="LGBTQIA+"> LGBTQIA+</label>
      <label><input type="checkbox" name="diversidade[]" value="Pessoa com deficiência"> Pessoa com deficiência</label>
      <label><input type="checkbox" name="diversidade[]" value="Prefiro Não Informar"> Prefiro Não Informar</label>
      <label>
        <input type="checkbox" id="outroCheckbox" name="diversidade[]" value="Outro"> Outro
      </label>

      <div id="outroTexto" style="display: none; margin-top: 8px;">
        <input type="text" name="diversidade_outro" placeholder="Especifique outro..." />
      </div>
    </div>
  </div>
</div>


            <!-- Segurança -->
            <fieldset>
                <legend>Segurança</legend>
                
                <div class="form-group">
                    <label for="senha">Senha*</label>
                    <input type="password" id="senha" name="senha" required
                           class="<?= isset($errors['senha']) ? 'error-field' : '' ?>">
                    <?php if (isset($errors['senha'])): ?>
                        <span class="error-message"><?= $errors['senha'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha*</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required
                           class="<?= isset($errors['confirmar_senha']) ? 'error-field' : '' ?>">
                    <?php if (isset($errors['confirmar_senha'])): ?>
                        <span class="error-message"><?= $errors['confirmar_senha'] ?></span>
                    <?php endif; ?>
                </div>
            </fieldset>

            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <script>

        
    // Autocompletar Endereço via CEP
    document.getElementById('cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length !== 8) return;
        
        this.classList.add('loading-cep');
        
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP não encontrado!');
                    return;
                }
                
                // Preenche os campos
                document.getElementById('endereco').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('estado').value = data.uf || '';
                document.getElementById('complemento').value = data.complemento || '';
                
                // Foca no campo número
                document.getElementById('numero').focus();
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                alert('Erro ao consultar CEP. Tente novamente.');
            })
            .finally(() => {
                this.classList.remove('loading-cep');
            });
    });
    
function toggleDiversidade() {
  const container = document.querySelector('.colapsavel');
  const conteudo = document.getElementById('diversidade-opcoes');

  container.classList.toggle('aberto');
  conteudo.style.display = conteudo.style.display === 'block' ? 'none' : 'block';
}
function toggleDiversidade() {
  const conteudo = document.getElementById('diversidade-opcoes');
  conteudo.style.display = conteudo.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener("DOMContentLoaded", function() {
  const outroCheckbox = document.getElementById("outroCheckbox");
  const outroTexto = document.getElementById("outroTexto");

  outroCheckbox.addEventListener("change", function () {
    outroTexto.style.display = this.checked ? "block" : "none";

    
  });
});


    </script>
</body>
</html>