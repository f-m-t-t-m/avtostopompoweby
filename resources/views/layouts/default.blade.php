<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="Layout">
            <header class="Layout__header">
                {% include 'components/header.twig' %}
            </header>
            
            <main class="Layout__main">
                {% block main %}{% endblock %}
            </main>
            
            <footer class="Layout__footer">
                {% include 'components/footer.twig' %}
            </footer>
        </div>
        
        <script type="text/javascript" src="/js/app.js"></script>
    </body>
</html>