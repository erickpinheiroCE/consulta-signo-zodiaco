<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado - Signo Zodiacal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .signo-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.4;
            z-index: 1;
            object-fit: cover;
            pointer-events: none;
        }
        
        .signo-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            display: block;
            border-radius: 50%;
            background: white;
            padding: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            border: 3px solid #f8f9fa;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
    <!-- IMAGEM PADRÃO NO FUNDO -->
    <img src="assets/imgs/zodiaco_bg.jpg" 
          alt="Zodíaco" 
          class="signo-background">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🔮 Signo do Zodiaco by: Erick Pinheiro 🔮</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="container">
            <?php
            // Mapeamento dos signos para arquivos JPG (ícones)
            $signo_imagens = array(
                'Áries' => 'aries.jpg',
                'Touro' => 'touro.jpg', 
                'Gêmeos' => 'gemeos.jpg',
                'Câncer' => 'cancer.jpg',
                'Leão' => 'leao.jpg',
                'Virgem' => 'virgem.jpg',
                'Libra' => 'libra.jpg',
                'Escorpião' => 'escorpiao.jpg',
                'Sagitário' => 'sagitario.jpg',
                'Capricórnio' => 'capricornio.jpg',
                'Aquário' => 'aquario.jpg',
                'Peixes' => 'peixes.jpg'
            );

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data_nascimento'])) {
                $data_input = trim($_POST['data_nascimento']);
                
                // Validação do formato
                if (!preg_match('/^\d{2}\/\d{2}$/', $data_input)) {
                    echo '<div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="alert alert-danger">
                                    <h4>Erro!</h4>
                                    <p>Formato de data inválido. Use o formato DD/MM (ex: 21/05)</p>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="index.php" class="btn btn-outline-primary">Voltar</a>
                                </div>
                            </div>
                          </div>';
                    exit;
                }
                
                list($dia, $mes) = explode('/', $data_input);
                $dia = (int)$dia;
                $mes = (int)$mes;
                
                // Validação da data
                $data_valida = true;
                if ($mes < 1 || $mes > 12) {
                    $data_valida = false;
                } else if ($dia < 1 || $dia > 31) {
                    $data_valida = false;
                } else {
                    $dias_no_mes = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    if ($dia > $dias_no_mes[$mes - 1]) {
                        $data_valida = false;
                    }
                }
                
                if (!$data_valida) {
                    echo '<div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="alert alert-danger">
                                    <h4>Data inválida!</h4>
                                    <p>Digite uma data válida no formato DD/MM</p>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="index.php" class="btn btn-outline-primary">Voltar</a>
                                </div>
                            </div>
                          </div>';
                    exit;
                }
                
                // Carrega XML
                if (!file_exists('signos.xml')) {
                    echo '<div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="alert alert-danger">
                                    <h4>Erro!</h4>
                                    <p>Arquivo signos.xml não encontrado.</p>
                                </div>
                            </div>
                          </div>';
                    exit;
                }
                
                $signos = simplexml_load_file('signos.xml');
                if ($signos === false) {
                    echo '<div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="alert alert-danger">
                                    <h4>Erro!</h4>
                                    <p>Erro ao carregar o arquivo XML.</p>
                                </div>
                            </div>
                          </div>';
                    exit;
                }
                
                // Encontra o signo
                $data_comparar = sprintf('%02d-%02d', $mes, $dia);
                $signo_encontrado = null;
                
                foreach ($signos->signo as $signo) {
                    $dataInicio = (string)$signo->dataInicio;
                    $dataFim = (string)$signo->dataFim;
                    
                    list($dia_inicio, $mes_inicio) = explode('/', $dataInicio);
                    list($dia_fim, $mes_fim) = explode('/', $dataFim);
                    
                    $inicio_compare = sprintf('%02d-%02d', $mes_inicio, $dia_inicio);
                    $fim_compare = sprintf('%02d-%02d', $mes_fim, $dia_fim);
                    
                    if ($inicio_compare <= $fim_compare) {
                        if ($data_comparar >= $inicio_compare && $data_comparar <= $fim_compare) {
                            $signo_encontrado = $signo;
                            break;
                        }
                    } else {
                        if ($data_comparar >= $inicio_compare || $data_comparar <= $fim_compare) {
                            $signo_encontrado = $signo;
                            break;
                        }
                    }
                }
                
                if ($signo_encontrado) {
                    $nome_signo = (string)$signo_encontrado->signoNome;
                    $imagem_arquivo = isset($signo_imagens[$nome_signo]) ? $signo_imagens[$nome_signo] : 'default.jpg';
                    $caminho_imagem = 'assets/imgs/' . $imagem_arquivo;
                    
                    // Verifica se a imagem do ícone existe
                    $imagem_existe = file_exists($caminho_imagem);
                    
                    echo '<div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6">
                              <div class="card shadow">
                                <div class="card-header bg-success text-white text-center">
                                  <h3 class="card-title mb-0">Seu signo é: ' . $nome_signo . '</h3>
                                </div>
                                <div class="card-body text-center">';
                    
                    // ÍCONE DO SIGNO DENTRO DO CARD
                    if ($imagem_existe) {
                        echo '<img src="' . $caminho_imagem . '" 
                                   alt="' . $nome_signo . '" 
                                   class="signo-icon">';
                    }
                    
                    echo '      <h4 class="text-primary mb-3">' . $nome_signo . '</h4>
                                  <p class="lead">' . (string)$signo_encontrado->descricao . '</p>
                                  <div class="row mt-4">
                                    <div class="col-md-6">
                                      <p class="text-muted"><strong>Período:</strong><br>' . (string)$signo_encontrado->dataInicio . ' a ' . (string)$signo_encontrado->dataFim . '</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="text-muted"><strong>Data informada:</strong><br>' . htmlspecialchars($data_input) . '</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="text-center mt-4">
                                <a href="index.php" class="btn btn-outline-primary btn-lg">Voltar ao Início</a>
                              </div>
                            </div>
                          </div>';
                } else {
                    echo '<div class="row justify-content-center">
                            <div class="col-md-6">
                              <div class="alert alert-warning">
                                <h4>Signo não encontrado!</h4>
                                <p>Não foi possível identificar um signo para a data: ' . htmlspecialchars($data_input) . '</p>
                              </div>
                              <div class="text-center mt-3">
                                <a href="index.php" class="btn btn-outline-primary">Voltar ao Início</a>
                              </div>
                            </div>
                          </div>';
                }
            } else {
                echo '<div class="row justify-content-center">
                        <div class="col-md-6">
                          <div class="alert alert-warning">
                            <h4>Acesso inválido!</h4>
                            <p>Esta página deve ser acessada através do formulário.</p>
                          </div>
                          <div class="text-center mt-3">
                            <a href="index.php" class="btn btn-outline-primary">Voltar ao Formulário</a>
                          </div>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>