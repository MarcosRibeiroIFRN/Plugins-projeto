<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<head>
<?php
/*
Plugin Name: Gerenciamento curadoriais 
Description: Pluggin para estudos. 
Version: 1.0
Author: Davi e Marcos
*/

// Função para criar as páginas no painel de administração
function criar_paginas_admin() {
    add_menu_page(
        'Gerenciador de Eventos',
        'Eventos',
        'manage_options',
        'gerenciador-eventos',
        'exibir_pagina_eventos',
        'dashicons-calendar-alt'
    );

}
add_action('admin_menu', 'criar_paginas_admin');

// Função para exibir a página de gerenciamento de eventos
function exibir_pagina_eventos() {
    ?>
    <div class="wrap">
        <h1>Gerenciador de Eventos</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=gerenciador-eventos&tab=eventos" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'eventos' ? 'nav-tab-active' : ''; ?>">Eventos Cadastrados</a>
            <a href="?page=gerenciador-eventos&tab=cadastro" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'cadastro' ? 'nav-tab-active' : ''; ?>">Cadastro de Eventos</a>
            <a href="?page=gerenciador-eventos&tab=responsaveis" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'responsaveis' ? 'nav-tab-active' : ''; ?>">Responsáveis de Eventos</a>
        </h2>

        <?php
        if (isset($_GET['tab']) && $_GET['tab'] == 'cadastro') {
            exibir_formulario_adicionar_evento();
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'eventos') {
            exibir_tabela_eventos();
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'responsaveis') {
            exibir_tabela_responsaveis_eventos(); // Chamando a função para exibir a tabela de responsáveis de eventos
        }
        ?>
    </div>
<?php
}

// Função para exibir o formulário de adicionar evento
function exibir_formulario_adicionar_evento() {
    global $wpdb;
    $table_temas = $wpdb->prefix . 'temas';
    $table_subtemas = $wpdb->prefix . 'subtemas';
    $temas = $wpdb->get_results("SELECT * FROM $table_temas");
    $subtemas = $wpdb->get_results("SELECT * FROM $table_subtemas");
    ?>
 <div class="wrap">
    <h1>Cadastro de Eventos</h1><br><br>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="adicionar_evento">
        <!-- Campos do formulário de evento -->
        <label for="nome">Nome:</label><br>
        <input class='form-control' type="text" id="nome" name="nome" required><br>
        
        <label for="descricao">Descrição:</label><br>
        <textarea class='form-control' id="descricao" name="descricao"></textarea><br>
        
        <label for="inicio">Início:</label><br>
        <input class='form-control' type="date" id="inicio" name="inicio"required><br>
        
        <label for="fim">Fim:</label><br>
        <input class='form-control' type="date" id="fim" name="fim"required><br>
        
        <label for="tipo">Tipo:</label><br>
        <select class='form-control' id="tipo" name="tipo" required>
            <option value="">Selecionar duração </option>
            <option value="Longa Duração">Longa Duração</option>
            <option value="Curta Duração">Curta Duração</option>
        </select><br>
       
        <!-- Seleção de Tema -->
        <label for="tema">Tema:</label><br>
        
        <select id="tema" name="tema"required>
            <option value="">Selecionar Tema</option>
            <?php foreach ($temas as $tema) : ?>
                <option value="<?php echo $tema->id; ?>"><?php echo $tema->Nome; ?></option>
            <?php endforeach; ?>
        </select>
            
        <button class='button-add' id="open-theme-modal" type="button">+</button><br>

        <!-- Seleção de Subtema -->
        <label for="subtema">Subtema:</label><br>
        
        <select id="subtema" name="subtema"required>
            <option value="">Selecionar Subtema</option>    
            <?php foreach ($subtemas as $subtema) : ?>
                <option value="<?php echo $subtema->id; ?>"><?php echo $subtema->Nome; ?></option>
            <?php endforeach; ?>
        </select>   
        
        <button class='button-add' id="open-subtema-modal" type="button">+</button><br><br><br><br><br><br>
        
        <!-- Botões para cadastrar temas e subtemas -->
        <button value="Adicionar Evento">Adicionar Evento</button>
    </form>
</div>


<!-- Dentro do modal de cadastro de tema -->
<div id="add-theme-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Cadastro de Tema</h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="form-criar-tema">
            <input type="hidden" name="action" value="criar_tema">
            <label for="nome-tema">Nome do Tema:</label><br>
            <input type="text" id="nome-tema" name="nome-tema" required><br><br>
            <input type="submit" value="Cadastrar Tema">
        </form>

        <!-- Tabela de Temas -->
        <?php exibir_tabela_temas_cadastrados($temas); ?>
    </div>
</div>


<div id="add-subtema-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Cadastro de Subtema</h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="form-criar-subtema">
            <input type="hidden" name="action" value="criar_subtema">
            <label for="nome-subtema">Nome do Subtema:</label><br>
            <input type="text" id="nome-subtema" name="nome-subtema" required><br><br>
            <input type="submit" value="Cadastrar Subtema">
        </form>
        
        <!-- Chamada da função para exibir a tabela de subtemas cadastrados -->
        <?php exibir_tabela_subtemas_cadastrados($subtemas); ?>
    </div>
</div>



    <?php
}
// Função para exibir a tabela de eventos cadastrados
function exibir_tabela_eventos() {
    global $wpdb;
    $table_eventos = $wpdb->prefix . 'eventos';
    $eventos = $wpdb->get_results("SELECT * FROM $table_eventos");
    ?>
    <h2>Eventos Cadastrados</h2>
    <table class="wp-list-table ">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Tema</th>
                <th>Subtema</th>
                <th>Ações</th> <!-- Adicionando uma coluna para as ações -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventos as $evento) : ?>
                <tr>
                    <td><?php echo $evento->id; ?></td>
                    <td><?php echo $evento->nome; ?></td>
                    <td><?php echo $evento->tipo; ?></td>
                    <td><?php echo $evento->descricao; ?></td>
                    <td><?php echo $evento->inicio; ?></td>
                    <td><?php echo $evento->fim; ?></td>
                    <td><?php echo obter_nome_tema_por_id($evento->tema_id); ?></td>
                    <td><?php echo obter_nome_subtema_por_id($evento->subtema_id); ?></td>
                    <td>
                        <!-- Botão para excluir o evento -->
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                            <input type="hidden" name="action" value="excluir_evento">
                            <input type="hidden" name="evento_id" value="<?php echo $evento->id; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
}
// Função para processar a exclusão de evento
function processar_exclusao_evento() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'eventos';
        $evento_id = intval($_POST['evento_id']);

        $wpdb->delete(
            $table_name,
            array('id' => $evento_id),
            array('%d')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=eventos'));
        exit;
    }
}

add_action('admin_post_excluir_evento', 'processar_exclusao_evento');
// Função auxiliar para obter o nome do tema por ID
// Função auxiliar para obter o nome do tema por ID
function obter_nome_tema_por_id($tema_id) {
    global $wpdb;
    $table_eventos = $wpdb->prefix . 'eventos';
    $table_temas = $wpdb->prefix . 'temas';
    $tema = $wpdb->get_row($wpdb->prepare("
        SELECT t.Nome 
        FROM $table_eventos AS e
        INNER JOIN $table_temas AS t ON e.tema_id = t.id
        WHERE e.tema_id = %d", $tema_id));
    return $tema ? $tema->Nome : 'N/A';
}

// Função auxiliar para obter o nome do subtema por ID
function obter_nome_subtema_por_id($subtema_id) {
    global $wpdb;
    $table_eventos = $wpdb->prefix . 'eventos';
    $table_subtemas = $wpdb->prefix . 'subtemas';
    $subtema = $wpdb->get_row($wpdb->prepare("
        SELECT s.Nome 
        FROM $table_eventos AS e
        INNER JOIN $table_subtemas AS s ON e.subtema_id = s.id
        WHERE e.subtema_id = %d", $subtema_id));
    return $subtema ? $subtema->Nome : 'N/A';
}


// Função para processar o formulário de adicionar evento
function processar_formulario_adicionar_evento() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'eventos';

        $nome = sanitize_text_field($_POST['nome']);
        $tipo = in_array($_POST['tipo'], array('longa', 'curta')) ? $_POST['tipo'] : 'longa';
        $descricao = sanitize_textarea_field($_POST['descricao']);
        $inicio = sanitize_text_field($_POST['inicio']);
        $fim = sanitize_text_field($_POST['fim']);
        $tema_id = intval($_POST['tema']); 
        $subtema_id = intval($_POST['subtema']); 

        $wpdb->insert(
            $table_name,
            array(
                'nome' => $nome,
                'tipo' => $tipo,
                'descricao' => $descricao,
                'inicio' => $inicio,
                'fim' => $fim,
                'tema_id' => $tema_id, 
                'subtema_id' => $subtema_id, 
            ),
            array(
                '%s', 
                '%s',
                '%s',
                '%s',
                '%s',
                '%d', 
                '%d', 
            )
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=eventos'));
        exit;
    }
}



add_action('admin_post_adicionar_evento', 'processar_formulario_adicionar_evento');

// SUBTEMAS
function exibir_formulario_criar_subtema() {
    ?>
    <br>
    <div class="wrap">
        <div id="add-event-modal" class="modal">
            <h1>Cadastro de Subtemas</h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="form-criar-subtema">
                <input type="hidden" name="action" value="criar_subtema">
                <label for="nome-subtema">Nome do Subtema:</label><br>
                <input type="text" id="nome-subtema" name="nome-subtema" required><br><br>
                <input type="submit" value="Cadastrar Subtema">
            </form>
        </div>
    </div>
    <?php
}

// Função para processar o formulário de cadastrar subtema
function processar_formulario_criar_subtema() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'subtemas';

        $nome_subtema = sanitize_text_field($_POST['nome-subtema']);

        $wpdb->insert(
            $table_name,
            array('nome' => $nome_subtema),
            array('%s')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=cadastro'));
        exit;
    }
}
add_action('admin_post_criar_subtema', 'processar_formulario_criar_subtema');


function exibir_tabela_subtemas_cadastrados($subtemas) {
    ?>
    <!-- Tabela de Subtemas Cadastrados -->
    <table class="wp-list-table ">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subtemas as $subtema) : ?>
                <tr>
                    <td><?php echo $subtema->id; ?></td>
                    <td><?php echo $subtema->Nome; ?></td>
                    <td>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="excluir_subtema">
                            <input type="hidden" name="subtema_id" value="<?php echo $subtema->id; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                        <!-- Adicionar aqui a opção de editar o subtema -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}


// Função para processar a exclusão de subtema
function processar_exclusao_subtema() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'subtemas';
        $subtema_id = intval($_POST['subtema_id']);

        $wpdb->delete(
            $table_name,
            array('id' => $subtema_id),
            array('%d')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=cadastro'));
        exit;
    }
}
add_action('admin_post_excluir_subtema', 'processar_exclusao_subtema');



//TEMAS
function exibir_formulario_criar_tema() {
    ?>
    <div class="wrap">
        <div id="add-event-modal" class="modal">
            <h1>Cadastro de Temas</h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="form-criar-tema">
                <input type="hidden" name="action" value="criar_tema">
                <label for="nome-tema">Nome do Tema:</label><br>
                <input type="text" id="nome-tema" name="nome-tema" required><br><br>
                <input type="submit" value="Cadastrar Tema">
            </form>
        </div>
    </div>
    <?php
}

// Função para processar o formulário de cadastrar tema
function processar_formulario_criar_tema() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'temas';

        $nome_tema = sanitize_text_field($_POST['nome-tema']);

        $wpdb->insert(
            $table_name,
            array('nome' => $nome_tema),
            array('%s')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=cadastro'));
        exit;
    }
}


function exibir_tabela_temas_cadastrados($temas) {
    ?>
    <!-- Tabela de Temas Cadastrados -->
    <table class="wp-list-table ">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($temas as $tema) : ?>
                <tr>
                    <td><?php echo $tema->id; ?></td>
                    <td><?php echo $tema->Nome; ?></td>
                    <td>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="excluir_tema">
                            <input type="hidden" name="tema_id" value="<?php echo $tema->id; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}





add_action('admin_post_criar_tema', 'processar_formulario_criar_tema');
// Função para processar a exclusão de tema
function processar_exclusao_tema() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'temas';
        $tema_id = intval($_POST['tema_id']);

        $wpdb->delete(
            $table_name,
            array('id' => $tema_id),
            array('%d')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=cadastro'));
        exit;
    }
}

add_action('admin_post_excluir_tema', 'processar_exclusao_tema');


//RESPONSÁVEIS
// Função para exibir a tabela de responsáveis de eventos

// Função para exibir o formulário de cadastro de responsáveis de eventos
function exibir_formulario_cadastro_responsavel() {
    ?>
    <h2>Cadastro de Responsáveis de Eventos</h2>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="form-cadastro-responsavel">
        <input type="hidden" name="action" value="criar_responsavel_evento">
        <label for="nome-responsavel">Nome do Responsável:</label><br>
        <input type="text" id="nome-responsavel" name="nome-responsavel" required><br><br>
        <label for="email-responsavel">E-mail do Responsável:</label><br>
        <input type="email" id="email-responsavel" name="email-responsavel"><br><br>
        <label for="area_atuacao">Área de Atuação:</label><br>
        <select id="area_atuacao" name="area_atuacao" required>
            <option value="">Selecione a área de atuação</option>
            <option value="Curador">Curador</option>
            <option value="Educador">Educador</option>
            <option value="Conservador">Conservador</option>
            <!-- Adicione outras opções conforme necessário -->
        </select><br><br>
        <label for="evento_id">Evento Relacionado:</label><br>
        <select id="evento_id" name="evento_id" required>
            <option value="">Selecione o Evento</option>
            <?php
            global $wpdb;
            $eventos = $wpdb->get_results("SELECT ID ,Nome  FROM {$wpdb->prefix}eventos ");
            foreach ($eventos as $evento) {
                echo '<option value="' . $evento->ID . '">' . $evento->Nome . '</option>';
            }
            ?>
        </select><br><br>
        <input type="submit" value="Cadastrar">
    </form>
    <?php
}


// Função para obter os nomes de todos os eventos
function obter_nomes_eventos() {
    $args = array(
        'post_type' => 'evento', // substitua 'evento' pelo nome correto do seu tipo de postagem de evento
        'posts_per_page' => -1
    );
    $eventos = get_posts($args);
    return $eventos;
}

// Função para processar o formulário de cadastrar responsável de evento
function processar_formulario_criar_responsavel() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'responsaveis';

        $nome_responsavel = sanitize_text_field($_POST['nome-responsavel']);
        $email_responsavel = sanitize_email($_POST['email-responsavel']);
        $area_atuacao = sanitize_text_field($_POST['area_atuacao']);
        $evento_id = intval($_POST['evento_id']); // ID do evento relacionado

        $wpdb->insert(
            $table_name,
            array(
                'nome' => $nome_responsavel,
                'email' => $email_responsavel,
                'area_atuacao' => $area_atuacao,
                'evento_id' => $evento_id // Adicionando o ID do evento
            ),
            array(
                '%s', // nome, email, area_atuacao são strings
                '%s',
                '%s',
                '%d' // evento_id é um número
            )
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=responsaveis'));
        exit;
    }
}
add_action('admin_post_criar_responsavel_evento', 'processar_formulario_criar_responsavel');


// Função para exibir a tabela de responsáveis de eventos cadastrados
function exibir_tabela_responsaveis_eventos() {
    global $wpdb;
    $table_responsaveis = $wpdb->prefix . 'responsaveis';
    $responsaveis = $wpdb->get_results("SELECT * FROM $table_responsaveis");
    ?>
    <!-- Tabela de Responsáveis de Eventos Cadastrados -->
    <div class="wrap">
        <h2>Responsáveis de Eventos Cadastrados</h2>
        <button id="open-add-responsible-modal" class="button button-primary">Adicionar Novo Responsável</button>
        <br><br>
        <table class="wp-list-table ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Área de Atuação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($responsaveis as $responsavel) : ?>
                    <tr>
                        <td><?php echo $responsavel->id; ?></td>
                        <td><?php echo $responsavel->nome; ?></td>
                        <td><?php echo $responsavel->email; ?></td>
                        <td><?php echo $responsavel->area_atuacao; ?></td>
                        <td>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <input type="hidden" name="action" value="excluir_responsavel_evento">
                                <input type="hidden" name="responsavel_id" value="<?php echo $responsavel->id; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Cadastro de Responsável -->
    <div id="add-responsible-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <?php exibir_formulario_cadastro_responsavel(); ?>
        </div>
    </div>

    <script>
        // Ao clicar no botão "Adicionar Novo Responsável", abrir o modal
        document.getElementById('open-add-responsible-modal').addEventListener('click', function() {
            document.getElementById('add-responsible-modal').style.display = 'block';
        });

        // Ao clicar no botão de fechar dentro do modal, fechar o modal
        document.querySelector('#add-responsible-modal .close').addEventListener('click', function() {
            document.getElementById('add-responsible-modal').style.display = 'none';
        });

        // Ao clicar fora do modal, fechar o modal
        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('add-responsible-modal')) {
                document.getElementById('add-responsible-modal').style.display = 'none';
            }
        });
    </script>
    <?php
}




// Função para processar a exclusão de responsável de evento
function processar_exclusao_responsavel_evento() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $wpdb;
        $table_name = $wpdb->prefix . 'responsaveis';
        $responsavel_id = intval($_POST['responsavel_id']);

        $wpdb->delete(
            $table_name,
            array('id' => $responsavel_id),
            array('%d')
        );

        wp_redirect(admin_url('admin.php?page=gerenciador-eventos&tab=responsaveis'));
        exit;
    }
}
add_action('admin_post_excluir_responsavel_evento', 'processar_exclusao_responsavel_evento');



#region Função para adicionar estilos e scripts personalizados
function adicionar_estilos_e_scripts_personalizados() {
    // Caminho para o arquivo CSS personalizado no seu plugin
    $custom_css_url = plugins_url('custom-styles.css', __FILE__);

    // Adiciona o arquivo CSS personalizado
    wp_enqueue_style('custom-styles', $custom_css_url);

    // Caminho para o arquivo JavaScript personalizado no seu plugin
    $custom_js_url = plugins_url('custom-script.js', __FILE__);

    // Adiciona o JavaScript personalizado
    wp_enqueue_script('custom-script', $custom_js_url, array('jquery'), '', true);
}
#endregion

// Adiciona os estilos personalizados e JavaScript à página de administração
add_action('admin_enqueue_scripts', 'adicionar_estilos_e_scripts_personalizados');
?>
