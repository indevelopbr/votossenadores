<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votos Senadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --green: #009C3B;
            --yellow: #FFCC00;
            --blue: #0033A0;
        }
        html, body {
            width: 100%;
            height: 100%;
        }
        body {
            background-color: #f4f4f4;
            font-family: 'Roboto', sans-serif;
        }

        /* Menu de Navegação */
        .navbar {
            background-color: var(--green);
        }
        .navbar-brand {
            font-weight: bold;
            color: var(--yellow);
        }
        .navbar-nav .nav-link {
            color: var(--yellow);
        }
        .navbar-nav .nav-link:hover {
            color: var(--blue);
        }

        /* Personalizando os cards */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .card-body h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--blue);
        }

        .card-body p {
            font-size: 1rem;
            color: var(--green);
        }

        /* Estilo para os filtros */
        .filter-input, .filter-select {
            border-radius: 30px;
            padding-left: 20px;
        }

        .filter-input {
            background-color: #fff;
            /* border: 2px solid var(--green); *
        }

        .filter-select {
            background-color: #fff;
            /* border: 2px solid var(--green); */
        }

        .filter-container {
            margin-bottom: 30px;
        }

        .filter-container input, .filter-container select {
            margin-bottom: 15px;
        }

        /* Título Principal */
        h1 {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--blue);
        }

        /* Brasão do Brasil */
        .brasao {
            width: 80px;
            height: auto;
        }

        /* Estilos do rodapé */
        footer {
            background-color: var(--blue);
            color: white;
            text-align: center;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Coat_of_arms_of_Brazil.svg/150px-Coat_of_arms_of_Brazil.svg.png" alt="Brasão" class="brasao"> Votos Senadores
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#votacoes">Votações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('partidos') ? 'active' : '' }}" aria-current="page" href="{{ route('partidos') }}">Partidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#senadores">Senadores</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <!-- Rodapé -->
    <footer class="mt-5">
        <p>&copy; 2025 Votos Senadores - Todos os direitos reservados</p>
    </footer>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica para filtro (exemplo básico)
        const filterName = document.getElementById('filterName');
        const filterParty = document.getElementById('filterParty');
        const filterUF = document.getElementById('filterUF');
        const partiesGrid = document.getElementById('partiesGrid');

        filterName.addEventListener('input', () => {
            // Implemente a lógica de filtro pelo nome
        });

        filterParty.addEventListener('change', () => {
            // Implemente a lógica de filtro pelo partido
        });

        filterUF.addEventListener('change', () => {
            // Implemente a lógica de filtro por UF
        });
    </script>
</body>
</html>
