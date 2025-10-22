<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Signo Zodiacal</title>
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
        }
        
        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }
        
        .btn {
            border-radius: 10px;
            font-weight: 600;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error {
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🔮 Signo do Zodiaco by: Erick Pinheiro 🔮</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white text-center">
                            <h3 class="card-title mb-0">Descubra seu signo</h3>
                        </div>
                        <div class="card-body">
                            <form id="signo-form" method="POST" action="show_zodiac_sign.php">
                                <div class="mb-3">
                                    <label for="data_nascimento" class="form-label">Data de nascimento</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="data_nascimento" 
                                           name="data_nascimento" 
                                           placeholder="DD/MM"
                                           maxlength="5"
                                           required
                                           pattern="\d{2}/\d{2}"
                                           title="Digite a data no formato DD/MM (ex: 21/05)">
                                    <div class="form-text text-center">Ex.: 21/05 (apenas dia e mês)</div>
                                    <div id="error-message" class="text-danger mt-1 text-center" style="display: none;">
                                        Data inválida! Digite um dia (1-31) e mês (1-12) válidos.
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" id="submit-btn">Descobrir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('data_nascimento').addEventListener('input', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
            
            validateDate(e.target.value);
        });

        document.getElementById('signo-form').addEventListener('submit', function(e) {
            var dataInput = document.getElementById('data_nascimento').value;
            if (!validateDate(dataInput)) {
                e.preventDefault();
                alert('Por favor, digite uma data válida no formato DD/MM (ex: 21/05)');
            }
        });

        function validateDate(dateString) {
            var errorDiv = document.getElementById('error-message');
            var input = document.getElementById('data_nascimento');
            var submitBtn = document.getElementById('submit-btn');
            
            if (!/^\d{2}\/\d{2}$/.test(dateString)) {
                errorDiv.style.display = 'block';
                input.classList.add('error');
                submitBtn.disabled = true;
                return false;
            }
            
            var parts = dateString.split('/');
            var dia = parseInt(parts[0], 10);
            var mes = parseInt(parts[1], 10);
            
            if (mes < 1 || mes > 12) {
                errorDiv.style.display = 'block';
                input.classList.add('error');
                submitBtn.disabled = true;
                return false;
            }
            
            var diasNoMes = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            if (dia < 1 || dia > diasNoMes[mes - 1]) {
                errorDiv.style.display = 'block';
                input.classList.add('error');
                submitBtn.disabled = true;
                return false;
            }
            
            errorDiv.style.display = 'none';
            input.classList.remove('error');
            submitBtn.disabled = false;
            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>